# Magento2 OpCache Manager

Allows the purge/clear/flush of the PHP OpCache via the CLI and Magento2 Backend


##Why
Because PHP OpCache is almost the de facto standard in current server/application setups. Most users/developers are 
usinga simple PHP script to clear the cache or simply ignore this cache. With this extension you can use it in your 
build/deployment process via the CLI. Also provided is an extension to the Magento2 backend. With a simple click you can
purge the cache.

## About me
I'm a DevOps engineer for a full service digital agency in the Netherlands. When possible I try to create opensource
scripts / extentions and tools. If you appriciate my work, please be so kind to donate so I can keep drinking beer.


[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](info@webfixit.nl)


### Additional cache management extended

<img alt="Magento2 Additional cache management" src="https://sysrant.com/wp-content/uploads/2017/11/1-1.png" style="width:100%"/>

### OpCache purge

<img alt="Magento2 OpCache purge" src="https://sysrant.com/wp-content/uploads/2017/11/2.png" style="width:100%"/>

### CLI Usage

```bash
php bin/magento opcache:status
```


Returns the opcache status:

```bash
bin/magento opcache:status
Array
(
    [opcache_enabled] => 1
    [cache_full] => 
    [restart_pending] => 
    [restart_in_progress] => 
    [memory_usage] => Array
        (
            [used_memory] => 78587744
            [free_memory] => 2068895904
            [wasted_memory] => 0
            [current_wasted_percentage] => 0
        )

    [interned_strings_usage] => Array
        (
            [buffer_size] => 20971520
            [used_memory] => 2283208
            [free_memory] => 18688312
            [number_of_strings] => 41723
        )

    [opcache_statistics] => Array
        (
            [num_cached_scripts] => 1289
            [num_cached_keys] => 1365
            [max_cached_keys] => 130987
            [hits] => 6
            [start_time] => 1510859030
            [last_restart_time] => 0
            [oom_restarts] => 0
            [hash_restarts] => 0
            [manual_restarts] => 0
            [misses] => 1289
            [blacklist_misses] => 0
            [blacklist_miss_ratio] => 0
            [opcache_hit_rate] => 0.46332046332046
        )

)

```


```bash
php bin/magento opcache:clear
```

Will clear the PHP OpCache

```bash
bin/magento opcache:clear
Cleared OpCache
```


## Install with Composer 

```bash
composer require webfixit/opcache
```
