# PHP Basics: Exercises

Now that we have the basics tucked away, let's walk through a few exercises together. 

These exercises will be algorithmic (i.e. they will follow a series of logical steps) and will help us solve specific problems through the lense of this new language. 

---

## Problem 1

Start with two variables that contain different numbers.

For example:

```php
$a = 10;
$b = 20;
```

Your goal is to **swap the values** of the two variables.

After swapping:

```text
$a should contain 20
$b should contain 10
```

### Your Task

Write a PHP script that:

1. Creates two variables with different numbers.
2. Echoes the values before the swap.
3. Swaps the values of the two variables.
4. Echoes the values after the swap.

### Rule

When swapping the values, do not type the numbers again. Use only variables.

---

## Problem 2

A right triangle has three sides: `a`, `b`, and `c`.

The longest side, `c`, is called the **hypotenuse**.

To find `c`, we use the **Pythagorean theorem**:

**a² + b² = c²**

For example, if:

```text
a = 3
b = 4
```

then:

```text
3² + 4² = 25

√25 = 5
```

So:

```text
c = 5
```

### Your Task

Write a PHP script that:

1. Creates a variable for `a`.
2. Creates a variable for `b`.
3. Calculates `c`.
4. Echoes the value of `c`.

### Hint

Use `**` to square a number:

```php
$a ** 2
```

Use `sqrt()` to find the square root:

```php
sqrt(25);
```

Try to combine these to calculate `c`.

---



## Problem 3

Start with a variable containing a **four-digit number**.

For example:

```php
$number = 1234;
```

Your goal is to add each digit together.

For `1234`:

```text
1 + 2 + 3 + 4 = 10
```

### Your Task

Write a PHP script that:

1. Stores a four-digit number in a variable.
2. Gets each digit from the number.
3. Adds the four digits together.
4. Echoes the final total.

### Hint

The modulus operator `%` gives you the **remainder after division**.

For example:

```php
1234 % 10
```

gives:

```text
4
```

This can help you get the last digit of a number.


