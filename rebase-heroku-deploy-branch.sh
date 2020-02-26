#!/bin/bash
timestamp=$(date +%s)
current_branch=$(git branch | sed -n -e 's/^\* \(.*\)/\1/p')

git checkout heroku-deploy
git branch -m heroku-deploy-"$timestamp"
git push origin -u heroku-deploy-"$timestamp"
git push origin --delete heroku-deploy
git checkout master
git checkout -b heroku-deploy
git cherry-pick b2ca72a9fe971110142f817680a82070046b4a38
git push origin heroku-deploy

git checkout "$current_branch"
