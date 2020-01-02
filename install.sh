#!/bin/bash
set -x # Show the output of the following commands (useful for debugging)

#get the original commit in case of a PR build
ORIGINAL_COMMIT=$(git show --pretty=%P HEAD | head -1 | cut -d\  -f 2)
ECHO $ORIGINAL_COMMIT
#creating the docker image, the tag represents the environment
SNAPSHOTTAG=''
if [[ $TRAVIS_PULL_REQUEST_BRANCH != '' ]]
then
  SNAPSHOTTAG='-staging'
elif [[ $TRAVIS_BRANCH == 'master' && $TRAVIS_PULL_REQUEST_BRANCH == '' ]]
then
  SNAPSHOTTAG=''
else
  SNAPSHOTTAG='-snapshot'
fi
docker build -t esac/website:0.0.$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG .
BUILDSTATUS=$?
docker login --username=esactravis --password=$DOCKER_PASSWORD
docker push esac/website:0.0.$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG
PUSHSTATUS=$?


#set github status when succeeds or fails
if [[ $BUILDSTATUS == '0' && $PUSHSTATUS == '0' ]]
then
  curl -X POST -H "Content-Type: application/json" -d \
  '{"state": "success",
  "target_url": "https://hub.docker.com/repository/docker/esac/website",
  "description": "build and push successful '$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG'",
  "context": "Docker image esac/website:0.0.'$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG'"}' \
  https://api.github.com/repos/esac-ic/esac.nl/statuses/$ORIGINAL_COMMIT\?access_token\=$github_token_wouter
else
  curl -X POST -H "Content-Type: application/json" -d \
  '{"state": "failure",
  "target_url": "https://hub.docker.com/repository/docker/esac/website",
  "description": "build or push unsuccessful '$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG'",
  "context": "Docker image esac/website:0.0.'$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG'"}' \
  https://api.github.com/repos/esac-ic/esac.nl/statuses/$ORIGINAL_COMMIT\?access_token\=$github_token_wouter
fi

