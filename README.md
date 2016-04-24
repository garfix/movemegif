# movemegif

An Animated GIF creation library in pure PHP.

This library focuses on the use of GD in the creation of animated gif images.

The library is written in PHP, and since it performs all its low-level calculations in PHP, it is quite slow.

* Requires PHP 5.3 or higher.
* Requires GD library (use `apt-get install php5-gd` or similar, to install)

Thanks a great deal to Matthew Flickinger for writing an awesome [GIF format explanation](http://www.matthewflickinger.com/lab/whatsinagif/index.html)

## Examples

The source code (directory "example") contains a few examples to help you on the way.

### Horse

The Horse example shows how to animate a series of same-sized images.

![Horse](https://raw.githubusercontent.com/garfix/movemegif/master/images/horse.gif)

The horse image was taken from [Wikipedia](https://en.wikipedia.org/wiki/Animated_cartoon)
 
### Pong
 
The elaborate PONG example shows how you can keep the filesize small while creating a large number of frames, by the use of

* _Clipping_: mark the area that you want redrawn in the frame. The library turns that clipping area into a frame. 
* _Stepping_: use multiple frames for a single step of your animation, to minimize the area that needs to be redrawn.  

In this example a complete frame takes 5kB in the compressed GIF format. The animation takes 270 steps and would take
over a MB if unclipped frames were used. Using the two techniques, the image just takes 179 kB.

![Pong](https://raw.githubusercontent.com/garfix/movemegif/master/images/pong.gif)

## Related

Another PHP animated GIF library was written by László Zsidi and can be found at [phpclasses.org](http://www.phpclasses.org/package/3163-PHP-Generate-GIF-animations-from-a-set-of-GIF-images.html)

The standard GIF library is written in C and can be found [here](https://sourceforge.net/projects/giflib/)

The GIF 89a specification is located [here](https://www.w3.org/Graphics/GIF/spec-gif89a.txt)
