# gallery-bundle

A simple gallery bundle that allows you to upload images, crop them and save references of them to your database.

Example config

This config assumes that your `http://some.host` points to `/home/user/project/uploads/public` and is configured properly to be publicly accessible

parameters.yml

```
parameters:
    uploads.root: /home/user/project/uploads
    uploads.host: 'http://some.host'
```

config.yml

```
parameters:
    revision: 1
    uploads.temp: %uploads.root%/temp
    uploads.public: %uploads.root%/public
    # Private is not used in this example, but you could point your webserver to index the public one
    # and have private files in the private and only serve them via symfony after authentication
    uploads.private: %uploads.root%/private 
    
wucdbm_gallery:
    configs:
        articles:
            path: '%uploads.public%/articles'
            host: '%uploads.host%/articles/%%s?v=%revision%'
            temp: '%uploads.public%/temp/articles'
            name: Articles
        products:
            path: '%uploads.public%/products/images'
            # Please notice th double percent sign in %%s?v - the first percent escapes the second
            # the %s is being replaced with the image relative path via sprintf() in the ImageManager
            host: '%uploads.host%/products/images/%%s?v=%revision%'
            temp: '%uploads.public%/temp/images/products'
            name: Products
            defaults:
                ratio: banner
                size: banner
    aspect_ratios:
        frontpage:
            width: 17
            height: 9
        banner:
            width: 300
            height: 250
    sizes:
        frontpage:
            width: 1700
            height: 900
        banner:
            width: 300
            height: 250
```