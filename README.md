
# Περί

_Κείμενο υπό αναθεώρηση_

Η εφαρμογή αυτή αφορά στη συλλογή αιτήσεων προτίμησης τοποθέτησης αναπληρωτών 
(λεπτομέρειες υπό αναθεώρηση)

# Εγκατάσταση της εφαρμογής 

_Κείμενο υπό αναθεώρηση_

## Λήψη κώδικα 

## Εγκατάσταση εξαρτήσεων 

```
composer install
```

## Προσαρμογή αρχείων ρυθμίσεων 

* params.php
    * `crypt-key-file` διαδρομή αρχείου που περιλαμβάνει το κλειδί κρυπτογράφησης - _διατηρήστε το εκτός πρόσβασης του web server_ 
    * `users` επιπλέον χρήστες με πρόσβαση στο σύστημα 
* web.php
    * `reCaptcha siteKey` και `reCaptcha secret` παράμετροι για το reCaptcha v2 που εμφανίζεται στην οθόνη σύνδεσης χρήστη (σε περιβάλλον ανάπτυξης `YII_ENV == 'dev'` το recaptcha δεν ενεργοποιείται)
* console.php 
* αρχείο κλειδιού κρυπτογράφησης 
    * Το αρχείο που θα περιέχει το κλειδί κρυπτογράφησης δηλώνεται στην 
    παράμετρο `cryptKeyFile` του component `crypt` (config/web.php και 
    config/console.php). 
    * Μπορείτε να δηλώσετε το αρχείο στη ρύθμιση του `crypt` component ή
    στο αρχείο παραμέτρων params.php στην παράμετρο `crypt-key-file` (προτιμότερο)
    * Για τη δημιουργία του κλειδιού ακολουθήστε τις [οδηγίες που θα βρείτε εδώ](https://github.com/defuse/php-encryption/blob/master/docs/Tutorial.md#scenario-1-keep-data-secret-from-the-database-administrator).

## Εγκατάσταση και αρχικοποίηση βάσης δεδομένων 

* Δημιουργία βάσης δεδομένων 
* Εκτέλεση migrations 
`./yii migrate`
`./yii migrate --migrationPath=@yii/log/migrations/`

## Δεδομένα για δοκιμές 

Για τον έλεγχο των λειτουργιών της εφαρμογής υπάρχουν διαθέσιμα δοκιμαστικά 
δεδομένα έτοιμα για χρήση. Για την εγκατάσταση των στοιχείων αυτών εκτελέστε 
από το ριζικό φάκελο την εντολή:

```
./yii fixture/load Application --namespace='app\tests\fixtures'
```

> Η παραπάνω εντολή θα εγκαταστήσει δοκιμαστικά δεδομένα στους πίνακες 
`Applicant`, `Choice`, `Prefecture` και `PrefecturePreference`. Ο πίνακας
`Application` θα εκκαθαριστεί.

Για αναλυτικές πληροφορίες για τα `Fixtures` και πως αυτά διαχειρίζονται 
από το Yii 2 [δείτε εδώ](http://www.yiiframework.com/doc-2.0/guide-test-fixtures.html).

### Δεδομένα από CSV αρχεία

Υπάρχει διαθέσιμη διαδικασία εισαγωγής στοιχείων μέσω console command.
Χρησιμοποιώντας αρχεία εισόδου σε μορφή CSV μπορεί να γίνει εισαγωγή δεδομένων
εκτελώντας την εντολή:

```
./yii import/parse file-with-applicant-info.csv file-with-choices.csv --runFixture=yes
```

Με την παράμετρο `runFixture=yes` τα δεδομένα θα εισαχθούν στη βάση δεδομένων 
αντικαθιστώντας τα ήδη καταχωρημένα δεδομένα.

Για αναλυτικές οδηγίες χρήσης του import command καθώς και των διαθέσιμων παραμέτρων
και μορφής των αρχείων εισόδου εκτελέστε την εντολή:

```
./yii help import 
```

Συγκεκριμένα στην έκδοση 1.0.0 η οθόνη βοήθειας για την προεπιλεγμένη εντολή import
είναι όπως παρακάτω: 

```
$ ./yii help import/parse 

DESCRIPTION

Parse and generate serialized data files with the applicant and choices data included in CSV files.


USAGE

yii import/parse <applicants_file> <choices_file> [...options...]

- applicants_file (required): string
  the filename of the CSV containing the applicant information

- choices_file (required): string
  the filename of the CSV containing the choices information


OPTIONS

--appconfig: string
  custom application configuration file path.
  If not set, default application configuration is used.

--csvDelimiter: string (defaults to ';')
  CSV delimiter character

--csvEnclosure: string (defaults to '"')
  CSV enclosure character

--csvSkipLines: string (defaults to 1)
  How many line to skip to get to the actual data

--fields_applicants: string (defaults to '1,2,3,4,5,6,9,11,12')
  Numeric index of the fields to retrieve. In order:
  For the applicants: 
  Prefecture preference, Lastname, Firstname, Fathername, Mothername, Phone, VAT, ID card, email

--fields_choices: string (defaults to '0,1,2,3')
  Numeric index of the fields to retrieve. In order:
  For the choices:
  Specialty, Position count, Position description, Prefecture (only the first letter in capital)

--runFixture: string (defaults to 'no')
  If "yes" the command also invokes the necessary fixture load command
```

# Περιβάλλον ανάπτυξης 

Η εφαρμογή αναπτύσσεται σε PHP, Bootstrap, JQuery, με χρήση του 
[Yii 2](http://www.yiiframework.com/) και αξιοποίηση επεκτάσεων μέσω βιβλιοθηκών.
Κατά την ανάπτυξη χρησιμοποιούνται οι βάσεις δεδομένων Mysql και Mariadb.

## Εξαρτήσεις εφαρμογής 

Για πλήρη λίστα των εξαρτήσεων της εφαρμογής εκτελέστε:

```
composer show --tree
```

Για να δείτε τις εξαρτήσεις για μια συγκεκριμένη βιβλιοθήκη, για παράδειγμα
της ίδιας της php εκτελέστε:

```
composer depends php
```

## Παρατηρήσεις - Διορθώσεις - Επεκτάσεις 

Για οποιοδήποτε θέμα αφορά την εφαρμογή πρέπει να γίνεται σχετική καταχώρηση στα 
[issues του έργου](https://github.com/spapad/admapp-applications-front/issues).
