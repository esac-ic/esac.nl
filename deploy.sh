#when push is being made to dev branch, run update.sh on dev server, if master run on prod server
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

#get commiter name:
AUTHOR_NAME="$(git log -1 $TRAVIS_COMMIT --pretty="%aN")"
COMMIT_MSG="$(git log -1 0a28b634470a505b74ad3ffda0fcdbcb0d6ccc94 --pretty="%s")"
#deploy to test server when brach is develop, deploy to prod server when branch is master
if [ $TRAVIS_BRANCH == 'develop' ]
then
  ssh deploy@beta.esac.nl './update.sh website 0.0.'$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG '"'$AUTHOR_NAME'"' '"'$COMMIT_MSG'"'
elif [ $TRAVIS_BRANCH == 'master' ]
then
  ssh deploy@esac.nl './update.sh' #account is not setup yet
fi

#ssh deploy@beta.esac.nl './update.sh 0.0.'$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG 0.0.25






