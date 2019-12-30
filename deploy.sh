#when push is being made to dev branch, run update.sh on dev server, if master run on prod server
#see the server config branch for the contents of the update.sh script

#setup ssh private key
echo $private_key_staging_server | base64 --decode > deploy-key
chmod 600 deploy-key
mv deploy-key ~/.ssh/id_rsa

#chmod 600 id_rsa.pub
#mv id_rsa.pub ~/.ssh/id_rsa.pub



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

#get commiter name for the version.txt  file:
AUTHOR_NAME="$(git log -1 $TRAVIS_COMMIT --pretty="%aN")"
COMMIT_MSG="$(git log -1 $TRAVIS_COMMIT --pretty="%s")"
COMMMIT_DATE="$(git log -1 $TRAVIS_COMMIT --pretty="%cD")"

#deploy to test server when brach is develop, deploy to prod server when branch is master
if [ $TRAVIS_PULL_REQUEST_BRANCH != '' ]
then
  ssh deploy@beta.esac.nl './update.sh website 0.0.'$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG '"'$AUTHOR_NAME'"' '"'$COMMIT_MSG'"' '"'$COMMMIT_DATE'"' '"'$TRAVIS_PULL_REQUEST_BRANCH'"'
elif [[ $TRAVIS_BRANCH == 'master' && $TRAVIS_PULL_REQUEST_BRANCH == '' ]]
then
  echo 'deploy to master'
#  ssh deploy@esac.nl './update.sh' #account is not setup yet
fi







