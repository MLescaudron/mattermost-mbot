# Mattermost-mbot
### Simple Web UI Interface to manage your bot responses
![alt picture](https://i.gyazo.com/fec2910faf22b2d6341171fc36e518fd.png)
##### In Mattermost
![alt picture](https://i.gyazo.com/e245f89ad2d00c663925957c286576de.png)

## Install

  - You need a website with php installed
  - Create a directory like "mattermods" and a subdirectory if you want "mbot"
  - Download index.php and move it on mbot directory
  - You need a read/write access on the directory (because of words.json generate and update)
  
#### Configuration
- Under index.php you can change bot username, bot icon and add token channels
- Create outgoing webhook for each channel like this params :
     ![alt picture](https://i.gyazo.com/88f2a3f5fba86c6e030ca2a5d3c090af.png)
- After creating the webhook, copy the generated token, and add it to the `$config` array, to the `token` key, in `index.php`. E.g.:

```
$config = [
    'username' => 'B4o4T', // username display on chat
    'icon_url' => 'https://les-404.xyz/img/B4o4T.png', // icon display on chat
    'token' => [
        'wx58em1ss3ya8gatdzddem9ada'
    ] // Channels token
```

### Add and edit messages/responses
- Access on the index.php like : https://your-domain.com/mattermods/mbot
- Add new messages (one line per messages and responses)

Each response are randomly pick


### Version
1.0

License
----

MIT
