# Lesson 25 — Uploading and Managing Images with PHP

In Lesson 24, we learned how to create and resize images using PHP's GD library. In this lesson, we'll allow users to upload photographs through a web form. We'll build a small **photo gallery application** that accepts JPEG and PNG images, creates two image sizes, saves information in MySQL, and displays a gallery.

## 1. Learning Objectives

By the end of this lesson, you should be able to:

- Explain how file uploads work in PHP.
- Create an HTML form that accepts image files.
- Access uploaded files using the `$_FILES` superglobal.
- Validate image type, size, and upload status.
- Generate a unique filename.
- Use GD to create a thumbnail and a 720-pixel-wide image.
- Save image information in MySQL.
- Retrieve image records and display a gallery.

## 2. Understanding Image Uploads

Until now, we have used HTML forms to collect text information such as names, emails, and passwords. We accessed those values in PHP using the $_POST superglobal.

When uploading an image, we are sending an actual file from the user's computer to the web server. PHP provides a separate superglobal called $_FILES to access uploaded files.

```text
User selects a photograph
          ↓
HTML form sends the file
          ↓
PHP receives the temporary upload
          ↓
PHP validates and processes it
          ↓
Image files and database record are saved
          ↓
Gallery displays the photograph
```

PHP initially places an uploaded file in a **temporary location**. We must process it before the request ends.

### The `$_FILES` superglobal

$_FILES is a built-in PHP associative array that contains information about uploaded files. For example, if our HTML form contains the following input:

`<input type="file" name="photo">`

PHP makes the uploaded file information available through $_FILES['photo']. The word photo comes from the name attribute of the HTML input.

| Property | Purpose |
|---|---|
| `name` | Original filename on the user's computer |
| `type` | File type reported by the browser; do not trust it for validation |
| `tmp_name` | Temporary file location on the server |
| `size` | File size in bytes |
| `error` | Upload status or error code |

```php
$_FILES['photo']['name'];
$_FILES['photo']['tmp_name'];
$_FILES['photo']['size'];
$_FILES['photo']['error'];
```

PHP provides built-in constants that help us determine whether an upload was successful.

| Constant | Value | Meaning |
|---|---|---|
| `UPLOAD_ERR_OK` | 0 | File uploaded successfully |
| `UPLOAD_ERR_INI_SIZE` | 1 | Server file size limit exceeded |
| `UPLOAD_ERR_PARTIAL` | 3 | File was only partially uploaded |
| `UPLOAD_ERR_NO_FILE` | 4 | User did not select a file |

We validate uploads because users might select an oversized file, an unsupported type, or a file renamed to look like a JPEG.

### PHP upload limits

PHP provides configuration settings that control how much data users can upload to the server.

```ini
upload_max_filesize = 10M
post_max_size = 12M
```

upload_max_filesize = 10M: This setting specifies the maximum size of an individual uploaded file. In this example, PHP allows an individual file of up to 10 MB.

post_max_size = 12M: This setting specifies the maximum size of the entire POST request. The POST request may contain uploaded files, text fields, and form encoding overhead. Therefore, we generally set post_max_size higher than upload_max_filesize.

Although the server allows individual files of up to 10 MB, our gallery application only allows photographs of up to 5 MB. Therefore, we enforce an additional restriction in our PHP code later.

## 3. Creating an Image Upload Form

Our first page, `index.php`, gives users a way to select and submit one photograph. A separate `upload.php` page will handle the submitted file.

### Application structure

Create the following folders and files:

```text
l25-image-uploads/
├── private/
│   └── connect.php
├── public/
│   ├── index.php
│   ├── upload.php
│   ├── gallery.php
│   └── images/
│       ├── thumbnails/
│       └── full/
└── init.sql
```

| File or folder | Why it exists |
|---|---|
| `public/index.php` | Displays the upload form |
| `public/upload.php` | Validates, resizes, saves, and records uploaded images |
| `private/connect.php` | Centralizes the MySQL connection |
| `public/gallery.php` | Displays saved photographs |
| `init.sql` | Creates the database and image table |
| `public/images/thumbnails/` | Stores small images |
| `public/images/full/` | Stores 720-pixel-wide images |

Create both image folders before testing. The web server must be able to write to them.

### Complete `public/index.php`

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload a Photograph</title>
</head>
<body>
    <main>
        <h1>Upload a Photograph</h1>
        <p>Select a JPEG or PNG photograph to add to the gallery.
           The maximum file size is 5 MB.</p>

        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="photo">Choose a photograph</label>
            <input type="file" id="photo" name="photo"
                   accept=".jpg,.jpeg,.png" required>
            <button type="submit">Upload Photograph</button>
        </form>

        <p><a href="gallery.php">View Photo Gallery</a></p>
    </main>
</body>
</html>
```

**Why these attributes matter:**

- `action="upload.php"` sends the form to our processing page.
- `method="post"` sends the file in the request body.
- `enctype="multipart/form-data"` attribute specifies how the browser encodes and sends form data to the web server. By default, HTML forms use application/x-www-form-urlencoded, which is suitable for submitting text information but does not transmit the actual contents of uploaded files. When uploading files such as photographs, PDFs, or documents, we must use multipart/form-data. This encoding type divides the form data into separate parts, allowing each text field and uploaded file to be transmitted with its own information,
- `type="file"` displays a file picker.
- `name="photo"` is the key used in `$_FILES['photo']`.
- `accept=".jpg,.jpeg,.png"` helps users choose supported files, but is **not** a security check.

**Test:** Open `index.php`. You should see a file picker, an upload button, and a gallery link. Uploading will not work until we create `upload.php`.

## 4. Uploading, Validating, and Processing Images

`upload.php` receives the file submitted by `index.php`. Its job is to check the upload, open the photograph with GD, and generate two image versions.

### Step 1 — Check the upload status

```php
if (!isset($_FILES['photo']) ||
    $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    exit('The photograph could not be uploaded.');
}
```

`isset()` checks that the expected file input exists. The `error` value tells us whether PHP received the file successfully.

### Step 2 — Check the file size

```php
$maxSize = 5 * 1024 * 1024;

if ($_FILES['photo']['size'] > $maxSize) {
    exit('The photograph must be smaller than 5 MB.');
}
```

PHP reports sizes in bytes; 5 × 1024 × 1024 is 5 MB.

### Step 3 — Inspect the actual file type

We must validate the actual file type before processing the uploaded photograph. We cannot rely on the file extension or the MIME type reported by the browser because these can be manipulated. For example, a user could rename a PDF file from `document.pdf` to `photo.jpg`, but this does not convert it into a JPEG image.

```php
$tempFile = $_FILES['photo']['tmp_name'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($tempFile);

if (!in_array($mimeType, ['image/jpeg', 'image/png'], true)) {
    exit('Only JPEG and PNG photographs are allowed.');
}
```
- `$_FILES['photo']['tmp_name']` retrieves the temporary location of the uploaded file on the server and stores it in `$tempFile`.

- `new finfo(FILEINFO_MIME_TYPE)` creates a File Information object configured to detect the MIME type of a file.

- `$finfo->file($tempFile)` examines the actual contents of the temporary file, detects its MIME type, and stores the result in `$mimeType`.

- `in_array()` checks whether the detected MIME type exists in the array of allowed MIME types (`image/jpeg` and `image/png`).

- The third argument, `true`, enables strict comparison, meaning both the value and data type must match.

- The `!` operator reverses the result of `in_array()`, making the condition true when the MIME type is not allowed.

- `exit()` displays an error message and stops the execution of the PHP script.

For example:

| Detected MIME Type | Result |
|---|---|
| `image/jpeg` | Accepted |
| `image/png` | Accepted |
| `image/gif` | Rejected |
| `image/webp` | Rejected |
| `application/pdf` | Rejected |

This validation helps prevent unsupported or disguised files from being processed by our application. If the detected MIME type is JPEG or PNG, PHP continues to the next validation step.

MIME type validation alone does not guarantee that the image is valid. We will perform additional checks before resizing and saving the photograph.

### Step 4 — Read the dimensions

```php
$dimensions = getimagesize($tempFile);

if ($dimensions === false) {
    exit('The uploaded file is not a valid image.');
}

$width = $dimensions[0];
$height = $dimensions[1];

if ($width < 720) {
    exit('Please choose a photograph at least 720 pixels wide.');
}
```

We need the original dimensions to calculate the resized height. For this lesson, we require images at least 720 pixels wide so we never need to enlarge a smaller photograph.

### Step 5 — Generate a unique filename

Two users might upload a file named `photo.jpg`. To avoid overwriting one upload with another, we generate a random name and use the correct extension.

```php
$extension = $mimeType === 'image/png' ? 'png' : 'jpg';
$filename = bin2hex(random_bytes(16)) . '.' . $extension;
```
The purpose of this step is to generate a unique filename for each uploaded photograph to prevent naming conflicts and accidental overwriting when multiple users upload files with the same name. The first line uses a ternary operator to determine the appropriate file extension (png or jpg) based on the validated MIME type. The second line uses random_bytes(16) to generate 16 random bytes and bin2hex() to convert them into a 32-character hexadecimal string. Finally, the concatenation operator (.) combines the generated string, a period, and the file extension to create a unique filename that will be used when saving the photograph and its metadata in the database.
Both generated images will share this filename but live in different folders.

### Step 6 — Load the image with GD

```php
if ($mimeType === 'image/png') {
    $image = imagecreatefrompng($tempFile);
} else {
    $image = imagecreatefromjpeg($tempFile);
}

if ($image === false) {
    exit('The photograph could not be opened.');
}
```

This is the same GD technique we practised in Lesson 24, now applied to a user-uploaded photograph.

### Step 7 — Resize the image


We need a **200-pixel-wide thumbnail** and a **720-pixel-wide larger version**. Both should preserve the photograph's proportions.

Rather than repeating the resizing code, we'll define a reusable function and call it twice with different widths and destination paths.

```php
function resizeImage($image, $width, $height, $newWidth, $destination, $mimeType)
{
    $newHeight = (int) round($height * ($newWidth / $width));
    $resized = imagecreatetruecolor($newWidth, $newHeight);

    if ($mimeType === 'image/png') {
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
    }

    imagecopyresampled(
        $resized, $image,
        0, 0, 0, 0,
        $newWidth, $newHeight,
        $width, $height
    );

    if ($mimeType === 'image/png') {
        return imagepng($resized, $destination);
    }

    return imagejpeg($resized, $destination, 85);
}
```

#### Function Parameters

| Parameter | Description |
|---|---|
| `$image` | The original GD image object. |
| `$width` | The original image width. |
| `$height` | The original image height. |
| `$newWidth` | The desired width of the resized image. |
| `$destination` | The file path where the resized image will be saved. |
| `$mimeType` | The validated MIME type of the original image. |

#### How the Function Works

1. **Calculate the new height:** Calculate the proportional height based on the new width to maintain the original aspect ratio and prevent image distortion.

2. **Create a blank image:** Use `imagecreatetruecolor()` to create a new blank image with the desired width and calculated height.
3. **Preserve PNG transparency:** Unlike JPEG images, PNG images support transparency through an alpha channel. The alpha channel stores transparency information, which determines how visible or see-through each pixel is. A pixel can be fully visible (opaque), partially transparent, or completely transparent. For example, a PNG logo with a transparent background allows the website's background colour to show through.

   Therefore, we need two additional functions when processing PNG images:

   - `imagealphablending($resized, false)` disables alpha blending so that the original pixel colours and their transparency information are retained when copying pixels into the new image.
   - `imagesavealpha($resized, true)` ensures that the alpha channel is preserved when saving the resized PNG image.

   Without these settings, transparent or partially transparent areas may lose their transparency or appear black.

   These additional steps are not required for JPEG images because JPEG only stores colour information and does not support an alpha channel. Therefore, when processing a JPEG image, PHP skips this conditional block and continues with the resizing process.
4. **Resize the original image:** Use `imagecopyresampled()` to copy and resize the original photograph into the new blank image. This function uses interpolation to produce a smoother, higher-quality resized image.

5. **Save the resized image:** Check the MIME type and use the appropriate function to save the image:
   - `imagepng()` saves the resized image in PNG format.
   - `imagejpeg()` saves the resized image in JPEG format with a quality setting of 85 out of 100.

6. **Return the result:** The function returns a Boolean value indicating whether the image-saving operation reports success or failure.

**Note:** At this stage, we are only defining the function, not executing it. In the next step, we will call this function twice to create and save the thumbnail and the larger version of the photograph.
### Step 8 — Save the two versions

```php
$thumbnailPath = __DIR__ . '/images/thumbnails/' . $filename;
$fullPath = __DIR__ . '/images/full/' . $filename;

$thumbnailSaved = resizeImage(
    $image, $width, $height, 200, $thumbnailPath, $mimeType
);

$fullSaved = resizeImage(
    $image, $width, $height, 720, $fullPath, $mimeType
);
```

`__DIR__` refers to the folder containing `upload.php`. It helps PHP build reliable server paths.

If either save fails, we should remove any partially saved files:

```php
if (!$thumbnailSaved || !$fullSaved) {
    if (file_exists($thumbnailPath)) {
        unlink($thumbnailPath);
    }
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
    exit('The photographs could not be saved.');
}
```

We do not keep an additional copy of the original upload because the gallery only needs these two versions. PHP removes the temporary upload when the request ends.

## 5. Saving Image Information in MySQL

The image files are stored in folders, but the gallery also needs a list of uploaded photographs. We'll store that information in a single MySQL table.

### Step 1 — Create `init.sql`

```sql
USE dmit2025;

CREATE TABLE IF NOT EXISTS images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(100) NOT NULL,
    mime_type VARCHAR(30) NOT NULL,
    original_width INT NOT NULL,
    original_height INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Run this SQL in your database management tool.

| Column | Purpose |
|---|---|
| `image_id` | Unique record identifier |
| `filename` | Filename shared by both versions |
| `mime_type` | JPEG or PNG |
| `original_width` | Original width |
| `original_height` | Original height |
| `uploaded_at` | Upload date and time |

We don't need two filename columns: the images use the same name in different folders.

### Step 2 — Create `private/connect.php`

This file holds the database connection so both `upload.php` and `gallery.php` can reuse it.

```php
<?php

$host = 'mysql';
$username = 'student';
$password = 'student';
$database = 'dmit2025';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    error_log($conn->connect_error);
    exit('Database connection failed.');
}

$conn->set_charset('utf8mb4');
```

Replace the credentials with those used by your local environment.

### Step 3 — Insert the image information

After the two image versions have been saved, `upload.php` inserts a database record using a prepared statement:

```php
require __DIR__ . '/../private/connect.php';

$sql = "INSERT INTO images
        (filename, mime_type, original_width, original_height)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ssii', $filename, $mimeType, $width, $height);
$stmt->execute();
```

`ssii` means two strings and two integers. Prepared statements keep the SQL separate from the inserted values.

### Complete `public/upload.php`

Here is the entire processing page assembled in one place:

```php
<?php
// 1. Check the upload.
if (!isset($_FILES['photo']) ||
    $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    exit('The photograph could not be uploaded.');
}

// 2. Check the size.
$maxSize = 5 * 1024 * 1024;
if ($_FILES['photo']['size'] > $maxSize) {
    exit('The photograph must be smaller than 5 MB.');
}

// 3. Check the actual file type.
$tempFile = $_FILES['photo']['tmp_name'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($tempFile);

if (!in_array($mimeType, ['image/jpeg', 'image/png'], true)) {
    exit('Only JPEG and PNG photographs are allowed.');
}

// 4. Read the dimensions.
$dimensions = getimagesize($tempFile);
if ($dimensions === false) {
    exit('The uploaded file is not a valid image.');
}

$width = $dimensions[0];
$height = $dimensions[1];

if ($width < 720) {
    exit('Please choose a photograph at least 720 pixels wide.');
}

// 5. Generate a unique filename.
$extension = $mimeType === 'image/png' ? 'png' : 'jpg';
$filename = bin2hex(random_bytes(16)) . '.' . $extension;

// 6. Load the photograph.
if ($mimeType === 'image/png') {
    $image = @imagecreatefrompng($tempFile);
} else {
    $image = @imagecreatefromjpeg($tempFile);
}

if ($image === false) {
    exit('The photograph could not be opened.');
}

// 7. Define the resizing function.
function resizeImage($image, $width, $height, $newWidth, $destination, $mimeType)
{
    $newHeight = (int) round($height * ($newWidth / $width));
    $resized = imagecreatetruecolor($newWidth, $newHeight);

    if ($mimeType === 'image/png') {
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
    }

    imagecopyresampled(
        $resized, $image,
        0, 0, 0, 0,
        $newWidth, $newHeight,
        $width, $height
    );

    if ($mimeType === 'image/png') {
        return imagepng($resized, $destination);
    }

    return imagejpeg($resized, $destination, 85);
}

// 8. Create the two image versions.
$thumbnailPath = __DIR__ . '/images/thumbnails/' . $filename;
$fullPath = __DIR__ . '/images/full/' . $filename;

$thumbnailSaved = resizeImage(
    $image, $width, $height, 200, $thumbnailPath, $mimeType
);
$fullSaved = resizeImage(
    $image, $width, $height, 720, $fullPath, $mimeType
);

if (!$thumbnailSaved || !$fullSaved) {
    if (file_exists($thumbnailPath)) {
        unlink($thumbnailPath);
    }
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
    exit('The photographs could not be saved.');
}

// 9. Save the metadata in MySQL.
require __DIR__ . '/../private/connect.php';

try {
    $sql = "INSERT INTO images
            (filename, mime_type, original_width, original_height)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $filename, $mimeType, $width, $height);
    $stmt->execute();
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    unlink($thumbnailPath);
    unlink($fullPath);
    exit('The photograph information could not be saved.');
}

// 10. Open the gallery.
header('Location: gallery.php');
exit();
```

The steps are: validate the upload, read its dimensions, generate a filename, load the image, save two versions, insert the database record, and redirect to the gallery.

**Note:** This is a classroom example. A public upload service needs additional safeguards, including stricter resource limits and access controls.

## 6. Displaying an Image Gallery

`gallery.php` shows photographs that have already been uploaded. It reads filenames from MySQL, displays thumbnails, and links them to the larger images.

### Complete `public/gallery.php`

```php
<?php
require __DIR__ . '/../private/connect.php';

$sql = "SELECT image_id, filename, uploaded_at
        FROM images
        ORDER BY uploaded_at DESC, image_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery</title>
</head>
<body>
    <main>
        <h1>Photo Gallery</h1>
        <p><a href="index.php">Upload Another Photograph</a></p>

        <?php if ($result->num_rows === 0): ?>
            <p>No photographs have been uploaded yet.</p>
        <?php else: ?>
            <div class="gallery">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $filename = htmlspecialchars(
                        $row['filename'], ENT_QUOTES, 'UTF-8'
                    );
                    $thumbnail = 'images/thumbnails/' . $filename;
                    $full = 'images/full/' . $filename;
                    ?>
                    <a href="<?= $full; ?>">
                        <img src="<?= $thumbnail; ?>"
                             alt="Uploaded photograph" width="200">
                    </a>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
```

The query lists the newest images first. `fetch_assoc()` retrieves one database row at a time. We construct the thumbnail and larger image paths using the stored filename. The `<img>` element displays the thumbnail, while the surrounding `<a>` opens the 720-pixel-wide version.

`htmlspecialchars()` escapes dynamic values before they appear in HTML.

### Test the complete application

1. Open `index.php`.
2. Select a valid JPEG or PNG photograph at least 720 pixels wide.
3. Click **Upload Photograph**.
4. Confirm that the browser redirects to `gallery.php`.
5. Confirm that the thumbnail appears.
6. Click the thumbnail to view the larger image.
7. Check that both image folders contain the same generated filename.
8. Check that the `images` database table contains the new record.

## 7. Exercise 29 — Working with Multiple Photographs

Use the completed application from this lesson. You do not need to create a new project.

### Part A — Upload photographs

Choose three different JPEG or PNG photographs. Each must be smaller than 5 MB and at least 720 pixels wide. Upload them **one at a time** using `index.php`.

### Part B — Check the image files

Confirm that each upload created:

- A 200-pixel-wide image in `images/thumbnails/`.
- A 720-pixel-wide image in `images/full/`.

Check that both versions preserve the original proportions.

### Part C — Check MySQL

Open the `images` table and confirm that every successful upload created a record with a different generated filename.

### Part D — Test validation

Try uploading an unsupported file, an image smaller than 720 pixels wide, and (if available) a file larger than 5 MB. Confirm that the application rejects invalid uploads.

### Part E — Test the gallery

Confirm that all successfully uploaded photographs appear as thumbnails, clicking each thumbnail opens the larger version, and the newest photographs appear first.
