# A Top-level domain parser

## FAQ

### Why?

I need a simple and fast top-level domain parser to analyse network traffic.

### How to use it?

See the tests.

### Which data does it use?

https://publicsuffix.org/list/public_suffix_list.dat

### How to learn about the domain naming system?

* https://publicsuffix.org/learn/
* https://www.icann.org/resources/pages/tlds-2012-02-25-en


### Why no PHP composer or Go mod?

Too simple to introduce them, unless there are enough users and interests.


### How to update the data?

Run `./generate.php`
