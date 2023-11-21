# Usage, example workflow

This document describes simple example of release branch creating. 
I recommend use one of public repositories to testing app.

I will use a [repository](https://github.com/janson-git/release-example-for-deploy.git) that specially created for testing purposes.

## Install the app

#### INSTALL (you need docker and docker-compose)

1. Clone and install `deploy` app
```shell
git clone https://github.com/janson-git/deploy.git
cd deploy
make install
```

or you can make this manually:
```shell
git clone git@github.com:janson-git/deploy.git
cd deploy
cp .env.example .env
docker-compose build
```

This step can get few munutes to build docker-container. It pulls docker image, 
install php with some extensions, composer, and nginx inside of docker container.

2. When build finished successfully, lets start app container.
```shell
make up
```
or
```shell
docker-compose up -d
```
The result of these commands is equal.

3. Open in your browser URL http://localhost:9088. It looks like this:
![Login page](./img/login_page.png)

4. Go to `Register` page and create account (all data stored locally in your app instance)
![Registration page](./img/register_page.png)

5. After click `Register`, you redirected to `Projects` page. Now it is empty. 
You need add repository cause of projects are based on repositories. Let's add one.
![Initial projects page](./img/initial_projects_page.png)

6. Click on `Add Repository` button, get the page and insert example URL `https://github.com/janson-git/release-example-for-deploy` in field. Then click `Save`
![Add your first repository to app](./img/add_repository_page.png)

7. After saving you saw the page where new repository presented. Ok, just click to `Projects` link in menu and starts to create new project!
8. Click on `Create new project` button on `Projects` page.

# TODO: FINISH THE HELP!
