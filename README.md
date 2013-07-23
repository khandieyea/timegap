Timegap
=======

[![Build Status](https://travis-ci.org/khandieyea/timegap.png)](https://travis-ci.org/khandieyea/timegap)

#WHY?
I built timegap because I wanted a time-passed or time-untill helper that only showed relevant information.
If something (such as a birthday) is 6 months away, do we really care about the seconds or minutes?
I basically got sick of seeing things like:

``Your next appointment is in 1 year, 3 months, 2 weeks, 4 days, 11 hours, 21 minutes, 11 seconds``

That's pretty over exagerated, but you get the idea. Timegap is my attempt at solving this.

#Composer
https://packagist.org/packages/khandieyea/timegap


#A usage example

Let's say your birthday is in 10 days:

```php
$gap = Timegap::createThen('a datetime stamp or unix tick that is 10 days from now');

echo "Your birthday is in: ";
echo $gap->output('weeks, days, hours, minutes, seconds', 2);
```

At first, this will result in

``Your birthday is in: 1 week, 4 days``

As the gap closes say 6 days away, the 'weeks' will disappear and we will see hours

``Your birthday is in: 6 days, 4 hours``

Singular/plural words are taken care of, when your birthday is just over a day away

``Your birthday is in: 1 day, 8 hours``

As we get closer and there's less than 1 day to go, again day drops off and minutes appear

``Your birthday is in: 4 hours, 46 minutes``

And again, when under an hour to go

``Your birthday is in: 24 minutes, 11 seconds``




#Dumb stuff you can do

```php
$gap = new Timegap::createThen('a datetime stamp or tick that is 11 hours away');

echo "Your taxi will arrive in: ";
echo $gap->output('hours, minutes, seconds');
echo "<br />Thats in precisely ";
echo $gap->output('seconds');
```

Would give you something like

```Your taxi will arrive in 10 hours, 59 minutes, 58 seconds```

```Thats in precisely 39598 seconds```
