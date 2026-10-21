#!/bin/bash
# Off-site backup (nightly timer and before every deploy): database dump + uploads (storage/app/public) to the restic repository on Backblaze B2.
# backup.env holds AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, RESTIC_REPOSITORY, RESTIC_PASSWORD and optionally BACKUP_PING_URL.
set -euo pipefail

cd /srv/esac
set -a; . ./backup.env; set +a
: "${AWS_ACCESS_KEY_ID:?}" "${AWS_SECRET_ACCESS_KEY:?}" "${RESTIC_REPOSITORY:?}" "${RESTIC_PASSWORD:?}"
mkdir -p dump
trap 'rm -f dump/esac.sql' EXIT

# Sessions and cache are dumped without their rows.
docker compose exec -T mysql sh -c '
  export MYSQL_PWD="$MYSQL_PASSWORD"
  options="--no-tablespaces --set-gtid-purged=OFF"
  structure_only=$(mysql -u"$MYSQL_USER" -N -e "SELECT table_name FROM information_schema.tables
    WHERE table_schema = DATABASE() AND table_name IN (\"sessions\", \"cache\", \"cache_locks\")" "$MYSQL_DATABASE") &&
  mysqldump -u"$MYSQL_USER" $options --single-transaction --routines --triggers --events \
    --ignore-table="$MYSQL_DATABASE.sessions" --ignore-table="$MYSQL_DATABASE.cache" --ignore-table="$MYSQL_DATABASE.cache_locks" \
    --databases "$MYSQL_DATABASE" &&
  mysqldump -u"$MYSQL_USER" $options --no-data "$MYSQL_DATABASE" $structure_only' < /dev/null > dump/esac.sql
grep -q "^-- Dump completed" dump/esac.sql

restic() {
  docker run --rm --env-file backup.env -v "$PWD/dump:/data/db:ro" -v restic-cache:/root/.cache/restic \
    --mount type=volume,source=esac_app-storage,target=/data/storage,volume-subpath=app/public,readonly \
    restic/restic:0.19.1 "$@"
}
restic backup --host esac-prod /data/db /data/storage
restic forget --keep-daily 30 --keep-monthly 12 --prune
restic check --read-data-subset=5%

if [ -n "${BACKUP_PING_URL:-}" ]; then curl -fsS -m 10 --retry 3 "$BACKUP_PING_URL" > /dev/null; fi
