#when push is being made to dev branch, run update.sh on dev server, if master run on prod server

ssh deploy@beta.esac.nl './update.sh'


