<?php

return [
    'title' => [
        'login' => 'Prisijungimas',
        'signup' => 'Registracija',
        'reset-password' => 'Slaptažodžio atstatymas',
        'login-with-social'=> 'arba prisijunkite su',
        'verification' => [
            'email' => 'Patvirtinimas'
        ],
        'invitation' => 'Pakvietimas',
    ],

    'message' => [
        'failed' => 'TŠie kredencialai neatitinka mūsų įrašų.',
        'password' => 'Pateiktas slaptažodis neteisingas.',
        'throttle' => 'Per daug bandymų prisijungti. Bandykite dar kartą po :seconds sekundžių.',
        'reset-password-instruction' => 'Į nurodytą el. pašto adresą bus išsiųstas laiškas su nuoroda į slaptažodžio keitimą',
        'dont-have-an-account' => 'Dar neturite paskyros?',
        'resend_verification_code' => 'Praradote kodą?',
        'verification_is_expired' => 'Kodo galiojimo laikas pasibaigė. Paspauskite "Siųsti dar kartą" ir gaukite naują kodą.',
        'verify' => [
            'email' => 'Įveskite ' . config('auth.verification.code_length') . ' skaitmenų kodą kurį gavote el. paštu.',
        ],
        'verification_to_many_attempts' => 'Per daug bandymų siųsti kodą. Pamėginkite vėliau.',
        'verification_expired' => 'Kodo galiojimas yra pasibaigęs.',
        'verification_not_found' => 'Patvirtinimo kodas nerastas.',
        'verification_resend_success' => 'Naujas patvirtinimo kodas sėkmingai išsiųstas!',
        'invitation_is_expired' => 'Pakvietimo galiojimas yra pasibaigęs. Kreipkitės į Jus pakvietūsį asmenį, kad išsiųstu naują pakvietimą.',
    ],

    'button' => [
        'login' => 'Prisijungti',
        'logout' => 'Atsijungti',
        'signup' => 'Registruotis',
        'send' => 'Siųsti',
        'forgot-password' => 'Pamiršote?',
        'verify' => 'Patvirtinti',
        'resend_verification_code' => 'Siųsti dar kartą',
        'has_invitation_code' => 'Turiu pakvietomo kodą',
    ],

    'prompt' => [
        'title' => [
            'logout' => 'Atsijungti?'
        ],
        'message' => [
            'logout' => 'Ar tikrai norite atsijungti?'
        ]
    ]
];
