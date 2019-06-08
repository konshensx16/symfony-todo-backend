# Symfony Todo API
---
###About the series
[Playlist to all the videos](https://www.youtube.com/playlist?list=PLqhuffi3fiMN_jVxqlIAILEp4avoBH4wc)
This series goes over how to create a really __BASIC__  Symfony API, yeah i didn't use API platform but that's okay, Below is a list of all the libraries that were used in the series.

##Libraries
* [FOSRestBundle
](https://github.com/FriendsOfSymfony/FOSRestBundle)
* [LexikJWTAuthenticationBundle
](https://github.com/lexik/LexikJWTAuthenticationBundle)
* [JWTRefreshTokenBundle
](https://github.com/gesdinet/JWTRefreshTokenBundle)
* [NelmioCorsBundle
](https://github.com/nelmio/NelmioCorsBundle)
* Will update if i forgot something

##Mistakes were made
I admit that a lot of the time i either said something that was misleading or i used the incorrect thing, let me explain.
* Sometimes i returned a status code of 200 instead of 201 when __CREATING__.
* __CLARIFICATION__: PATCH vs PUT, i said that PATCH is used to update a part of the document, i might have said it's used to update just one field or property but that's not true, it's used to update multiple parts but not the entire document, otherwise why you would use PATCH instead of PUT, thign mistake happened because the example i was looking had just one property and i said it used to update just one.

##Contributing
This project is open for everyone, if you notice anything wrong with my code, or even this readme, any mistakes are welcome.

##Questions
Maybe this should've been above with the Contribution section but doesn't matter, if you have any question i think it would be nice to create an issue (Please open an issue no matter how _trivial_ you think the question is, no one will charge you money for it) that way people with similar questions can look them up.