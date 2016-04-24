# movemegif

An Animated GIF creation library in pure PHP.

This library focuses on the use of GD in the creation of animated gif images.

* Requires PHP 5.3 or higher.
* Requires GD library (use `apt-get install php5-gd` or similar, to install)

## Features

* Looping
* Frame positioning (left, top)
* Clipping of frame areas.
* Specifying a color for transparent pixels
* The duration of a frame in 1/100s of a second
* Explicit use of global and local color tables (default = global)
* End-of-frame actions: leave as is (= default), restore to previous frame, restore to background
* Adding comments

There are three ways to create a frame:

* Use an existing image (FileImageCanvas)
* Create a frame with GD lib functions (GdCanvas)
* Create frames based on a string of indexes and a color table array (StringCanvas)

The library is written in PHP, and since it performs all its low-level calculations in PHP, it is quite slow.

## Comments

* A duration of 2/100-ths of a second is the minimum, since browsers
    [impose a slowness fine](http://superuser.com/questions/569924/why-is-the-gif-i-created-so-slow) for values of 0 and 1.
* GIF (or rather Netscape's Application Block) does not allow you to start looping a subset of all frames.
* While GIF allows you to _restore to background color_ at the end of a frame, browsers interpret this by "restoring"
 to the pixels that show though from the page on which the image is located.

Thanks a great deal to Matthew Flickinger for writing an awesome [GIF format explanation](http://www.matthewflickinger.com/lab/whatsinagif/index.html)

## Examples

The source code (directory "example") contains a few examples to help you on the way.

Each example shows a different animation strategy. Choose the strategy that best suits your needs.
Combinations of the strategies are also possible, since all settings are done on the frame level.

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

The strategy here is to draw each frame completely (with GD lib), but to create frames only of the areas of the image
 that have changed.

![Pong](https://raw.githubusercontent.com/garfix/movemegif/master/images/pong.gif)

## Related

Another PHP animated GIF library was written by László Zsidi and can be found at [phpclasses.org](http://www.phpclasses.org/package/3163-PHP-Generate-GIF-animations-from-a-set-of-GIF-images.html)

The standard GIF library is written in C and can be found [here](https://sourceforge.net/projects/giflib/)

The GIF 89a specification is located [here](https://www.w3.org/Graphics/GIF/spec-gif89a.txt)
