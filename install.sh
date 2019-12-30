#!/bin/bash
set -x # Show the output of the following commands (useful for debugging)



#creating the docker image, the tag represents the environment
SNAPSHOTTAG=''
if [ $TRAVIS_BRANCH == 'develop' ]
then
  SNAPSHOTTAG='-staging'
elif [ $TRAVIS_BRANCH == 'master' ]
then
  SNAPSHOTTAG=''
else
  SNAPSHOTTAG='-snapshot'
fi
docker build -t esac/website:0.0.$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG .
docker login --username=esactravis --password=$DOCKER_PASSWORD
docker push esac/website:0.0.$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG
