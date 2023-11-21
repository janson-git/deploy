# Add GutHub token

This page describes how to add your Personal Access Token (PAT) from GitHub. 
You need to add PAT if you want work with private GitHub repositories.
PAT is not needed for local operations (like create packs, merge branches, or create checkpoints).

You can add it later if you just want to try `deploy` with public repositories and without any push operations.

## Create Personal Access Token

Please, read the GitHub page about tokens: 
[Managing your personal access tokens](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens)

I recommend you to use fine-grained tokens for your `deploy` app. 
You can list repositories that allowed to access with your token 
(and you can update this list if you need). 
It is flexible, convenient way to manage access for your token.

After creating token on GitHub side, copy your token value and add it on `Profile` page of your `deploy` app.
