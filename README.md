# Symfony Todo API
---
### About the series
[Playlist to all the videos](https://www.youtube.com/playlist?list=PLqhuffi3fiMN_jVxqlIAILEp4avoBH4wc)
This series goes over how to create a really __BASIC__  Symfony API, yeah i didn't use API platform but that's okay, Below is a list of all the libraries that were used in the series.

## Libraries
* [FOSRestBundle
](https://github.com/FriendsOfSymfony/FOSRestBundle)
* [LexikJWTAuthenticationBundle
](https://github.com/lexik/LexikJWTAuthenticationBundle)
* [JWTRefreshTokenBundle
](https://github.com/gesdinet/JWTRefreshTokenBundle)
* [NelmioCorsBundle
](https://github.com/nelmio/NelmioCorsBundle)
* Will update if i forgot something

## Mistakes were made
I admit that a lot of the time i either said something that was misleading or i used the incorrect thing, let me explain.
* Sometimes i returned a status code of 200 instead of 201 when __CREATING__.
* __CLARIFICATION__: PATCH vs PUT, i said that PATCH is used to update a part of the document, i might have said it's used to update just one field or property but that's not true, it's used to update multiple parts but not the entire document, otherwise why you would use PATCH instead of PUT, thign mistake happened because the example i was looking had just one property and i said it used to update just one.

# Create a user manually (for testing)

This is a complete section by itself because it seems like couple of people have had their share of headaches because of this, to create a user manually and save it in the dabase here's what i personally do, __IT WORKS__

### Encode the password using the symfony command 

This is extremely important especially if you have no idea what the hell is happening, use the following command to generate a hashed version of your password and then jut copy the value that was printed to you and put in your database.
Command: __php bin/console security:encode-password__
You will be prompted to enter the plaintext password that you want to be hashed, after clicking the return key (enter key) the hashed key will be generated for you.

### Fields that i care about in the database

There are three main fields that i care about, and they are the email, password, roles.
Theses are the value that i enter for my testing user just to make sure that my authentication system works.

* email: __admin@admin.com__
* roles: __["ROLE_USER"]__
* password:   __\$2y\$13\$XPRUnZV1V9NI7Tya/a7fh.8/86VIZTA3LIA.4mKeuadnFDy2HqFNu__


__NOTE ABOUT THE PASSWORD: This password is the hashed version of [000000], just six zeros, and i got this result because the algorithm in the security file is set to auto, if you have bcrypt it could be different if you have a2rgony or whatever the hell that one is called, you will get something different__

# Troubleshooting and stuff

## Bad credentials error

This one error could happen because of multiple error due to the very bad error reporting of this library or whatever is responsible for that, the main thing to keep in mind is anything could lead to this type of problems

## Examples
* Database server not running will throw this error.
* The password is not encoded in the correct way.
* You didn't generate the public and private keys files correctly.
* The passphrase used during public and private key generation does not match the one used in the configuration file.
* TODO (Add more as more emails come in)

# Contributing
This project is open for everyone, if you notice anything wrong with my code, or even this readme, any mistakes are welcome.

# Questions
Maybe this should've been above with the Contribution section but doesn't matter, if you have any question i think it would be nice to create an issue (Please open an issue no matter how _trivial_ you think the question is, no one will charge you money for it) that way people with similar questions can look them up.