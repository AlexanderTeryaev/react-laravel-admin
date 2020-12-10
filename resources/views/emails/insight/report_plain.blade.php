Rapport de l'utilisation de Marmelade

Suite au déploiement en cours de Marmelade dans votre entreprise, nous vous communiquons un rapport de l'utilisations de vos collaborateurs.
Bonne lecture !

{{ $insight->totalUsers }}  nombre d'utilisateurs totaux
{{ $insight->lastWeekUsers }}  évolution du nombre d'utilisateurs depuis le dernier reporting
{{ $insight->totalUserAnswers }}  nombre de questions répondues
{{ $insight->lastWeekUserAnswers }}  nombre de questions répondues depuis le dernier reporting

10 questions avec le plus de bonnes réponses (ID/Question/Nombre de bonnes réponses):
@foreach($insight->topTenGoodQuestions as $q)
    - #{{ $q->question_id }} - {{ $q->question }} - {{ $q->total }}
@endforeach


10 questions avec le plus de mauvaises réponses (ID/Question/Nombre de mauvaises réponses):
@foreach($insight->topTenBadQuestions as $q)
    - #{{ $q->question_id }} - {{ $q->question }} - {{ $q->total }}
@endforeach

Pour plus d'informations ou si vous avez des questions, vous pouvez directement répondre à cet e-mail.

L'équipe Marmelade

