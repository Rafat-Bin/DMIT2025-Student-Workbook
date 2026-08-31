# Setup Guide: Local Development Environment for PHP

PHP is a server-side language. This means that any PHP script needs to be run by an interpreter (the PHP 'engine') installed on a server before a browser can render the output for the user. Because of this, we need to set up our development environment before we can start learning the language. 

In this course, we will use Docker Desktop, which will allow us to run our development environment locally (i.e. on our own computers). It works by letting us build and run a container that has everything needed to run our applications, including the operating system, software, libraries, code, and configuration settings that would normally be on a remote server.

This guide will help you set up your development environment for DMIT2025 - PHP/MySQL. 


---

## Installing Docker Desktop

### Windows Setup

1. Start by [downloading Docker Desktop](https://www.docker.com/products/docker-desktop).

2. Click **Download for Windows**.

3. Run the **Docker Desktop Installer**.

4. During installation, ensure the **“WSL 2”** option is selected.

   Note: Windows Subsystem for Linux (WSL) is a feature of Windows that allows you to run a Linux environment on your Windows machine, without the need for a separate virtual machine or dual booting.

5. Restart your computer after installation.

6. Open **Docker Desktop** from the Start menu.

7. Wait for Docker to finish starting up (the whale icon in the taskbar stops animating).

8. Verify installation by opening **Command Prompt** and typing:

````bash
   docker --version
````


### Troubleshooting Common Issues (in Windows)

* **Docker fails to start**
  Ensure virtualization is enabled in your BIOS.

* **WSL 2 issues**
  Run Windows Update and ensure WSL 2 is properly installed.

* **Permission errors**
  Run **Command Prompt** as Administrator.


---


### macOS Setup

1. Start by [downloading Docker Desktop](https://www.docker.com/products/docker-desktop).

2. Click **Download for Mac**.

3. Choose the correct version for your CPU:

   * **Apple Silicon (M1/M2)**

   * **Intel Chip**

   Note: The last Intel-based Macs were released in late 2020 and early 2021. If you are unsure which processor you have, click on `(Apple Icon) > About This Mac` in the top-level menu.

4. Open the downloaded **.dmg** file.

5. Drag **Docker.app** to the **Applications** folder.

6. Open **Docker Desktop** from **Applications**.

7. Grant any system permission prompts.

8. Wait for Docker to finish starting up.

9. Verify installation in Terminal:

````shell
   docker --version
````


### Troubleshooting Common Issues (in macOS)
* **Permission denied errors**
  Ensure proper system permissions are granted in **System Settings**.

* **Installation hanging**
  Restart your Mac and try again.

* **CPU architecture mismatch**
  Double‑check you downloaded the correct version for your Mac’s chip.

---

## Setting Up Your Development Environment

1. Open your course workbook in **VS Code**.

  Note: Make sure that `apache-dirlist.conf`, `compose.yml`, and `Dockerfile` are in the **root** of your project directory. If they are in a subdirectory, the pathing will be broken.

2. From the top level menu select `Terminal > New Terminal`. 

3. If this is your first time using this container (i.e. this development environment), you must build it by using the following command:

```shell
  docker compose build
```

4. Wait for the build to complete. During the build process, Docker will need to download an image and install it. Depending upon your computer's specifications and network speeds, this can take several seconds to a few minutes. 

5. After the build is finished, you will need to start your environment with the following command:

```shell
  docker compose up -d
```

  Note: The `-d` flag lets containers run in the background; this will allow your prompt to return immediately.

6. Once the container is 'up', you will be able to access your local server in your browser using the following URI: 

```txt
  http://localhost:8080/dmit-2025-workbook/
```

  Note: If you change the name of your workbook, your container name (everything after `:8080/`) will need to be whatever you changed the name of your workbook to.

7. When you are finished working, you can either close Docker Desktop and VS Code, or issue the following command:

```shell
  docker compose down
```

---


## Notes on Local vs. Remote Environments

The LAMP stack that you install and use within this Docker container is entirely **local**. This means that it runs on your computer and is only accessible through your computer.

  Note: In networking, this is what 'localhost' means - it's just a way of the computer referring to itself. 

Because your PHP files are not 'live' or published on the world wide web, this allows you to develop freely and work out any potential bugs or security issues without any risk to you or your web application. This is how most PHP developers currently work. 

However, if you would like to publish your website, you will need a domain name and hosting on a PHP-enabled server. After purchasing these, you will need to transfer your files to the remote web server through SFTP (secure file transfer protocol) or other means. In the development world, we refer to this step as 'pushing to prod' (pushing files to the production server) or 'going live'.
