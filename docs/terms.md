# Terms used in this app

### Repository

This is simple: it is just a git repository that used to work and create release branches in it.

You need it cause of `deploy` app work with code and branches. And `release` branch as a result, also would be pushed to repository.

### Project

This is just an entity to grouping packs by repositories. Each project may contain few packs (releases) in work.

For example, you can create a project to backend and a project to frontend.

### Pack

Think about PACK like a plan of release. This is a list of branches which planned to be merged together to deliver.
For example, you can create packs `release_2023-01-15` and `release_2023-01-29` and other, when you needed.

Maybe you want to deliver branches `issue-with-auth` and `issue-with-user-XXX` in first release. But other branches - in next one. You can make it easily.

Packs related to project, and it means that packs in different projects can be named equally. 
You can plan `release_2023-01-15` for frontend and backend project simultaneously.

### Build (or Checkpoint)

It is release branch in fact. When you create a build, it created as a new branch. And you can merge all planned branches by one button click.
When build will ready you can push it to your repository.

If your work branches is updated you can update the release branch just by one click on `Merge branches` button.
