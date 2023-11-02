# Mercure flash messager with sweetAlert setup


## Configuration
* Copy & past from  
```
NeoxNotifyBundle
└─── assets
│   └─── controllers
│       └─── coreController.js
│       └─── notify_controller.js

to your root app 
└─── assets
│   └─── controllers
│       └─── coreController.js
│       └─── notify_controller.js
```

## Npm Configuration

npm -i @sweetalert2/themes sweetalert2

in twig Template 
```twig
    {{ stream_notifications() }}  // this is for mercure "PUSH" notification
    {{ neox_notify(topics=['/my/topic/1','/my/topic/2','/my/topic/3']) }} // this is for mercure "wss" notification
```
