
# Laravel API - TDD 

Laravel app developed using TDD approach.

This project includes
- Show posts in the feed with their information (Image, description, date, author) including total likes and the last 5 usernames who liked the post.
- Feed is public (Doesn’t need authentication), paginated, and order by creation date.
- Authenticated Users can like/unlike posts.
- Users can remove their posts, with the image file.
- Users can like/unlike other posts.
- Users can see all likes of a specific post.
- Send a notification to other users when a new post is added. (using Database channel)
- Automatically delete posts 15 days old.


## Run Locally

Clone the project

```bash
  git clone https://github.com/yogesh16/Laravel-API-TDD.git
```

Go to the project directory

```bash
  cd Laravel-API-TDD
```

Install dependencies

```bash
  composer install
```

Start the server

```bash
  php artisan start
```


## Running Tests

To run tests, run the following command

```bash
  php artisan test
```


## Authors

- [@yogesh16](https://www.github.com/yogesh16)


## License

[MIT](https://choosealicense.com/licenses/mit/)




[![MIT License](https://img.shields.io/apm/l/atomic-design-ui.svg?)](https://choosealicense.com/licenses/mit)
