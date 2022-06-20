# PHP-Player-plus-plus (pp++ for short)

Embedded php interpreter with `SDL2/SDL2_mixer/SDL2_image` support for PSVita.  
For when you don't know better language.

## Building

### Requirements:

* Latest [VitaSDK](https://vitasdk.org/)
* [Vita PHP8 port](https://github.com/isage/vita-packages-extra)

### Compiling

```
mkdir build
cd build
cmake -DCMAKE_BUILD_TYPE=Release ..
make
```

### Customizing

Entry script is located in `data/index.php`.  
Livearea assets in sce_sys.  
App name and id in CMakeLists.txt
