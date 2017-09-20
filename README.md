
# Δεδομένα για δοκιμές 

Για τον έλεγχο των λειτουργιών της εφαρμογής υπάρχουν διαθέσιμα δοκιμαστικά 
δεδομένα έτοιμα για χρήση. Για την εγκατάσταση των στοιχείων αυτών εκτελέστε 
από το ριζικό φάκελο την εντολή:

```
./yii fixture/load Applicant --namespace='app\tests\fixtures'
```

> Η παραπάνω εντολή θα εγκαταστήσει δοκιμαστικά δεδομένα στους πίνακες 
`Applicant` και `Choice`.

Για αναλυτικές πληροφορίες για τα `Fixtures` και πως αυτά διαχειρίζονται 
από το Yii 2 [δείτε εδώ](http://www.yiiframework.com/doc-2.0/guide-test-fixtures.html).


# Άλλες πληροφορίες 

Based on a [Yii 2](http://www.yiiframework.com/) Basic Project Template skeleton.
