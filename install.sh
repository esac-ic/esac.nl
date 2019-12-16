#!/bin/bash
set -x # Show the output of the following commands (useful for debugging)
    
# Import the SSH deployment key
openssl aes-256-cbc -K $encrypted_705ea9aa11f9_key -iv $encrypted_705ea9aa11f9_iv -in deploy_key.enc -out deploy_key -d
rm deploy-key.enc # Don't need it anymore
chmod 600 deploy-key
mv deploy-key ~/.ssh/id_rsa

#creating the docker image, the tag represents
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
