#when push is being made to dev branch, run update.sh on dev server, if master run on prod server
#see the server config branch for the contents of the update.sh script

#setup ssh private key
echo $private_key_staging_server | base64 --decode > deploy-key
chmod 600 deploy-key
mv deploy-key ~/.ssh/id_rsa


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

#get commiter name for the version.txt  file:
AUTHOR_NAME="$(git log -1 $TRAVIS_COMMIT --pretty="%aN")"
COMMIT_MSG="$(git log -1 $TRAVIS_COMMIT --pretty="%s")"
COMMMIT_DATE="$(git log -1 $TRAVIS_COMMIT --pretty="%cD")"

#The variable  $$TRAVIS_PULL_REQUEST_BRANCH is  empty when the run is not a PR,  so it will deploy to beta.esac.nl when there is a PR
if [ $TRAVIS_PULL_REQUEST_BRANCH != '' ]
then
  ssh deploy@beta.esac.nl './update.sh website 0.0.'$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG '"'$AUTHOR_NAME'"' '"'$COMMIT_MSG'"' '"'$COMMMIT_DATE'"' '"'$TRAVIS_PULL_REQUEST_BRANCH'"'
elif [[ $TRAVIS_BRANCH == 'master' && $TRAVIS_PULL_REQUEST_BRANCH == '' ]]
then
  echo 'deploy to master'
#  ssh deploy@esac.nl './update.sh' #account is not setup yet
fi







