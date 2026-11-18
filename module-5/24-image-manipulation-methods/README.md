# Lesson 24 — Introduction to Image Processing with PHP

In Lesson 23, we built a secure administrator login system. In this lesson, we'll learn a different PHP skill: **creating and resizing images**.

PHP can take a large photograph and create smaller copies for a website. For example, a website might use a small thumbnail and a larger version of the same photograph.

We'll use PHP's **GD library**. We will not upload images or store image information in MySQL yet; those topics come later.

We'll build three small PHP pages in order. First, we'll check that GD works. Next, we'll generate a coloured image to see how PHP creates image output. Finally, we'll resize a supplied photograph and save the result. Each page introduces a skill we'll use in the next one.

## 1. Learning Objectives

By the end of this lesson, you should be able to:

- Explain what the PHP GD library does.
- Check whether GD is enabled.
- Create a simple coloured image with PHP.
- Load an existing JPEG photograph.
- Find an image's width and height.
- Resize a photograph without stretching it.
- Save thumbnail and larger copies of a photograph.

## 2. Introduction to PHP GD

**GD** is a graphics library that PHP can use to create and modify images. For example, we can create a coloured square or make a large photograph smaller.

Image processing usually follows three steps:

1. **Load** an image into memory.
2. **Modify** the image.
3. **Save** or display the result.

We'll save our changes to new filenames so the original photograph stays unchanged.

### Prepare the project

We are keeping the examples in one small project so we can run each PHP page separately and compare the results. The numbered filenames show the order in which we will work through the examples.

Create a project folder named `l24-image-processing` with this structure:

```text
l24-image-processing/
└── public/
    ├── 00-gdinfo.php
    ├── 01-square.php
    ├── 02-photo.php
    └── images/
        └── original.jpg
```


The photograph is also included with this README as `images/original.jpg`.

The `public` folder holds the PHP pages we will open in the browser. The `images` folder holds the photograph we start with and the new files PHP will save. We do not need an HTML form for this lesson because the photograph is already on the server.

### Check whether GD is enabled

**Why are we creating this page?** Image functions will not work if the GD extension is missing. Instead of writing the whole lesson and discovering a problem later, we will make a small test page first.

We call it `00-gdinfo.php` because it is our first check. Its only job is to tell us whether GD is available; it does not create an image.

Create `public/00-gdinfo.php`:

```php
<?php

if (extension_loaded('gd')) {
    echo 'GD is enabled.';
} else {
    echo 'GD is not enabled.';
}
```

Open `00-gdinfo.php` through your local PHP server. You should see **GD is enabled.** If not, the GD extension needs to be enabled in your PHP environment before you continue.

The `extension_loaded('gd')` check returns true when GD is available. We use an `if` statement to display a useful message for either result. Once this page confirms GD is working, we can move on to creating an image.

## 3. Creating a Simple Image

**Why are we creating this page?** Before changing an existing photograph, we need to understand how PHP makes an image and sends it to a browser. A plain coloured square is an easy starting point because there is no file to load and no resizing calculation.

We'll create `01-square.php` to generate a **300 × 300 green square**. Unlike a typical PHP page that outputs HTML, this page will output image data. That is why we need to send the correct content type before displaying it.

Create `public/01-square.php`:

```php
<?php

$image = imagecreate(300, 300);

$green = imagecolorallocate($image, 0, 255, 0);

imagefill($image, 0, 0, $green);

header('Content-Type: image/png');

imagepng($image);
```

### How does it work?

The order matters: we create the image first, choose a colour, fill the image, and then send it to the browser. We cannot fill an image before it exists.

- `imagecreate(300, 300)` creates an image that is 300 pixels wide and 300 pixels tall.
- `imagecolorallocate()` defines a colour using **red, green, and blue (RGB)** values. Here, `(0, 255, 0)` means green.
- `imagefill()` fills the image with that colour.
- `header('Content-Type: image/png')` tells the browser that PHP is sending a PNG image. `image/png` is called a **MIME type**.
- `imagepng()` sends the image to the browser.

Open `01-square.php` in your browser. You should see a green square, not a normal HTML page. The browser can display it because `header()` tells it to expect a PNG image, and `imagepng()` sends the actual image data.

This page **displays** the square but does not save a PNG file to the `images` folder. In the next example, we will save an image to disk instead.

**Try it:** Change the colour values to `(255, 0, 0)` for red or `(0, 0, 255)` for blue. You can also change the dimensions in `imagecreate()`.

## 4. Resizing and Saving a Photograph

**Why are we creating this page?** Websites often need a smaller copy of a photograph so it fits the layout and does not require visitors to download an unnecessarily large file. We want PHP to do that work for us rather than manually editing each photo.

In `02-photo.php`, we'll start with the supplied `original.jpg`, make a copy that is **720 pixels wide** (or keep the original width if it is already smaller), and save that copy as `resized.jpg`. We'll preserve the photograph's proportions so it doesn't look stretched.

This page works differently from `01-square.php`: it reads an existing file and **saves a new file**. The browser will show a short success message, not the photograph itself.

We'll build the code in steps, then put it all together in `02-photo.php`.

### Step 1 — Load the JPEG

```php
$image = imagecreatefromjpeg('images/original.jpg');
```

This loads the photograph into memory so GD can work with its pixels. We must load the photograph **before** we can measure or resize it. This step does not change the original file.

Functions for different image formats: JPG / JPEG:	imagecreatefromjpeg(), PNG:	imagecreatefrompng(), GIF: imagecreatefromgif(), WebP: imagecreatefromwebp().


### Step 2 — Find the dimensions

```php
$width = imagesx($image);
$height = imagesy($image);
```

`imagesx()` gives us the width, and `imagesy()` gives us the height. We need both numbers because changing only the width without calculating a matching height could distort the photograph.

### Step 3 — Calculate the new size

```php
$newWidth = min(720, $width);
$newHeight = round($height * ($newWidth / $width));
```

The new width will be at most 720 pixels. The height is calculated from the original proportions so the photograph is not stretched.

For example, a 2400 × 1600 image resized to 720 pixels wide becomes 720 × 480 pixels:

```text
1600 × (720 / 2400) = 480
```

The relationship between width and height is called the **aspect ratio**.

`min(720, $width)` prevents us from enlarging a photograph that is already smaller than 720 pixels. For this exercise, use a photograph that is **at least 720 pixels wide** so the larger output can be 720 pixels wide.

### Step 4 — Create and fill the resized image

```php
$resized = imagecreatetruecolor($newWidth, $newHeight);

imagecopyresampled(
    $resized,     // Destination image
    $image,       // Original image

    0,            // Destination X
    0,            // Destination Y

    0,            // Source X
    0,            // Source Y

    $newWidth,    // Destination width
    $newHeight,   // Destination height

    $width,       // Original width
    $height       // Original height
);
```

We need two images in memory at this point: the original photograph and an empty destination for the smaller version. We do **not** resize the original file directly.

`imagecreatetruecolor()`function creates a new blank, true-color image in memory. It supports approximately 16.7 million colors, making it suitable for photographs. At this point, we have two image objects. The original image contains the photograph, while the new image is a blank black canvas waiting for the resized photograph.

 `imagecopyresampled()` function copies pixels from the original image into the destination image, resizing them using interpolation to produce a smoother, higher-quality result.

The first two arguments identify the images: `$resized` is the **destination** (where the smaller photograph will go), and `$image` is the **source** (our original photograph). The four zeros mean that we start copying from the top-left corner of both images. The final four arguments give the new width and height, followed by the original width and height.

You don't need to memorize all ten arguments. The important idea is that GD copies the original photograph into a new image at the size we calculated.

### Step 5 — Save the new JPEG

```php
imagejpeg($resized, 'images/resized.jpg', 85);
```

The imagejpeg() function converts the image into JPEG format and saves it to a file. This saves the resized photograph as `resized.jpg` inside the `images` folder. The number `85` is the JPEG quality setting (out of 100). We use a **different filename** so the original `original.jpg` remains unchanged.

We save the image only after loading, measuring, and resizing it. If we tried to save before those steps, we would not have the resized result yet.

If we want to save the resized image in a different format, we need to change both the image-saving function and the file extension. For example, we use imagejpeg($resized, 'images/resized.jpg', 85) for JPEG, imagepng($resized, 'images/resized.png', 6) for PNG, imagewebp($resized, 'images/resized.webp', 85) for WebP, and imagegif($resized, 'images/resized.gif') for GIF. In JPEG and WebP, the third parameter represents image quality ranging from 0 to 100, whereas in PNG, it represents the compression level ranging from 0 to 9. The resizing logic remains the same regardless of the output format. We can also load an image in one format and save it in another. Simply changing the file extension is not enough because the saving function determines the actual image format.

### Complete `02-photo.php`

Now we can combine all five steps into one working page. Create `public/02-photo.php` and enter the complete code below:

```php
<?php

$image = imagecreatefromjpeg('images/original.jpg');

$width = imagesx($image);
$height = imagesy($image);

$newWidth = min(720, $width);
$newHeight = round($height * ($newWidth / $width));

$resized = imagecreatetruecolor($newWidth, $newHeight);

imagecopyresampled(
    $resized,
    $image,
    0, 0, 0, 0,
    $newWidth, $newHeight,
    $width, $height
);

imagejpeg($resized, 'images/resized.jpg', 85);

echo 'Image resized successfully.';
```

Open `02-photo.php` through your local PHP server. PHP will run the resizing code and save the new photograph. You should see:

```text
Image resized successfully.
```

The message tells us the script reached its last line. To check the actual result, look inside `public/images/`. You should have:

```text
images/
├── original.jpg
└── resized.jpg
```

Open both photographs and compare them. The resized copy should keep the same proportions as the original.

**Note:** Running the script again overwrites `resized.jpg`. The `images` folder must be writable by PHP. For this lesson, we're using a known, valid JPEG; we'll introduce checks for user-uploaded files in Lesson 25.

### A note about image memory

GD loads images into server memory while PHP processes them. Larger photographs use more memory. In modern PHP, these image objects are released automatically when the script finishes, so we don't need a separate cleanup function for these examples.

## 5. Exercise 28

**Purpose:** Practise changing an existing image-processing script rather than writing a new one from scratch. Different pages on a website may need different image sizes, so we will create two versions of the same photograph.

For this exercise, create **two versions** of the same photograph:

- A **200-pixel-wide thumbnail**.
- A **720-pixel-wide image**.

We'll create both using our existing photograph. We won't upload anything yet. You'll run `02-photo.php` once for each size, changing the width and output filename between runs. This lets you focus on the resizing steps you've just learned.

### Part A — Create a thumbnail

Open `02-photo.php` and change the new width to 200:

```php
$newWidth = min(200, $width);
```

Then change the saved filename:

```php
imagejpeg($resized, 'images/thumbnail.jpg', 85);
```

Run the script. Check that `thumbnail.jpg` was created and that the photograph is not stretched.

### Part B — Create the full-sized version

Change the width to 720:

```php
$newWidth = min(720, $width);
```

Change the saved filename:

```php
imagejpeg($resized, 'images/full.jpg', 85);
```

Run the script again. If the original photograph is at least 720 pixels wide, `full.jpg` should be exactly 720 pixels wide.

### Part C — Compare the results

Your `images` folder should now contain:

```text
images/
├── original.jpg
├── resized.jpg
├── thumbnail.jpg
└── full.jpg
```

Open the images and compare their sizes. The thumbnail should be noticeably smaller than the 720-pixel image, but the subject should have the same shape in both. If an image looks stretched, revisit the calculation for `$newHeight`.
