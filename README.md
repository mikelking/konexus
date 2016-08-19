konexus
=======


[![Deployment status from DeployBot](https://konexus.deploybot.com/badge/56046447847840/4052.svg)](http://deploybot.com)


- This system has been crafted in an attempt to make is submodule installable. Assuming you have a repository setup with WordPress installed in a wordpress subdirectory you would add the following to your .gitmodules file in the root of the tree via the git submoldule add comand.

```
cd REPO/wordpress/wp-content/
git submodule add --force --name mu-plugins https://github.com/mikelking/singleton_base.git mu-plugins
```


```
[submodule "mu-plugins"]
        path = wordpress/wp-content/mu-plugins
        url = https://github.com/mikelking/singleton_base.git
```

- The above submodule system is a work in progress and anyone who may be following this project will notice the reoganization of the file system hierarchy into a flat tree. the plugin-stub is intended as a model for starting a new child plugin and should be copied into the plugins directory. Also make note of the files names as they have been crafted to load with the WordPress mu-plugin autoloader in a specific order.
