# blogs-web

## Step 1: Import Database

- Import the provided SQL file (`db/blogs_app.sql`) into your local MySQL database.

## Step 2: Configure Database Connection

- Open the file `includes/dbh.inc.php`.
- Modify the variables within this file to match your local database settings.

## Step 3: Configure Session Settings

- Open the file `includes/config_session.inc.php`.
- Change the value **domain** on line 9 based on your domain name. If you are using localhost, type:`'domain' => 'localhost' `
- Adjust the value **path** on line 10 based on the name of the folder where this project is located. If you are using the domain name as the root, you can put a slash only '/'.

## Step 4: Set Environment Variables

- Open the file `includes/env.inc.php`.
- Modify the `APP_URL` variable based on your domain name. If you are using localhost, you can leave it as is.
- You can add email sending variables later, if you want to use email verification.


## ⚠️ NOTE

```diff
- Please be advised that thorough testing and optimization of the website are pending(You may face some bugs or errors).
- I will commit the latest updates once these processes are completed
```

### Licence

[GPL-3.0 license](LICENSE)
