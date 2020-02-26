#!/bin/bash
git checkout heroku-deploy
git branch -m heroku-deploy-old
git push origin -u heroku-deploy-old
git push origin --delete heroku-deploy
git checkout master
git checkout -b heroku-deploy
git cherry-pick 13afb00c9a15cc783f369c465c4b6634869f7d0f
git push origin heroku-deploy
git branch -D heroku-deploy-old && git push origin --delete heroku-deploy-old
