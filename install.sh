#!/bin/bash
set -x # Show the output of the following commands (useful for debugging)



#creating the docker image, the tag represents the environment
SNAPSHOTTAG=''
if [ $TRAVIS_PULL_REQUEST_BRANCH != '' ]
then
  SNAPSHOTTAG='-staging'
elif [[ $TRAVIS_BRANCH == 'master' && $TRAVIS_PULL_REQUEST_BRANCH == '' ]]
then
  SNAPSHOTTAG=''
else
  SNAPSHOTTAG='-snapshot'
fi
docker pull esac/website:0.0.29

#docker build -t esac/website:0.0.$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG .
#docker login --username=esactravis --password=$DOCKER_PASSWORD
#docker push esac/website:0.0.$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG
