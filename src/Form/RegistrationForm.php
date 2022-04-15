<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\CaptchaType;
use App\Form\Type\PrivacyType;
use App\Services\Helpers\FormHelper;
use App\Services\Helpers\IconHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationForm extends AbstractType
{
    const BUSINESS = 'isBusiness';
    const AGENCY = 'isAgency';
    const PLUGIN = 'isPlugin';
    const HAS_AGREE_TERMS = 'hasAgreeTerms';
    const PRIVACY_READ_TEXT = 'privacyReadText';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options[self::PLUGIN]) {
            $builder->add('username', TextType::class, [
                FormHelper::LABEL => 'registration.fields.username',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 ',
                    FormHelper::ICON => IconHelper::USER],
                FormHelper::CONSTRAINTS => [
                    new NotBlank(),
                ],
            ]);
        }
        $builder
            ->add('publicAdministration', CheckboxType::class, [
                FormHelper::LABEL => 'registration.fields.pa',
                // FormHelper::ATTR => $attr,
                FormHelper::REQUIRED => true])
            ->add('email', EmailType::class, [
                FormHelper::LABEL => 'email',
                FormHelper::ATTR => [
                    'autocomplete' => 'off',
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                    FormHelper::ICON => IconHelper::EMAIL],
                FormHelper::CONSTRAINTS => [
                    new NotBlank([
                        'message' => "email.empty",
                    ]),
                    new Email([
                        'mode' => 'html5',
                        'message' => "email.notValid",
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                FormHelper::LABEL => 'password',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                    FormHelper::ICON => IconHelper::PASSWORD],
                'mapped' => false,
                FormHelper::CONSTRAINTS => [
                    new NotBlank([
                        'message' => 'password.empty',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'password.length',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                ],
            ]);
        if (!$options[self::BUSINESS] && !$options[self::AGENCY]) {
            $builder->add('firstName', TextType::class, [
                FormHelper::LABEL => 'registration.fields.name',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                    FormHelper::ICON => IconHelper::USER],
                FormHelper::CONSTRAINTS => [
                    new NotBlank(),
                ],
            ])
                ->add('lastName', TextType::class, [
                    FormHelper::LABEL => 'registration.fields.lastName',
                    FormHelper::ATTR => [
                        'class' => 'form-control',
                        FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                        FormHelper::ICON => IconHelper::USER],
                    FormHelper::CONSTRAINTS => [
                        new NotBlank(),
                    ],
                ])
                ->add('taxCode', TextType::class, [
                    FormHelper::LABEL => 'registration.fields.taxCode',
                    FormHelper::ATTR => [
                        'class' => 'form-control',
                        FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                        FormHelper::ICON => IconHelper::TAXCODE,
                    ],
                    FormHelper::CONSTRAINTS => [
                        new Length([
                            'max' => 16,
                            'min' => 16,
                            'maxMessage' => 'tax.length',
                        ]),
                    ],
                ]);

        } else {
            $builder->add('businessName', TextType::class, [
                FormHelper::LABEL => 'registration.fields.businessName',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                    FormHelper::ICON => IconHelper::BUSINESS],
                FormHelper::CONSTRAINTS => [
                    new NotBlank(),
                ],
            ])
                ->add('taxCode', TextType::class, [
                    FormHelper::REQUIRED => false,
                    FormHelper::LABEL => 'registration.fields.taxCode',
                    FormHelper::ATTR => [
                        'class' => 'form-control',
                        FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                        FormHelper::ICON => IconHelper::TAXCODE],
                    FormHelper::CONSTRAINTS => [
                        new Length([
                            'max' => 16,
                            'min' => 16,
                            'maxMessage' => 'tax.length',
                        ]),
                    ],
                ])
                ->add('vatCode', TextType::class, [
                    FormHelper::REQUIRED => false,
                    FormHelper::LABEL => 'registration.fields.vatCode',
                    FormHelper::ATTR => [
                        'class' => 'form-control',
                        FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                        FormHelper::ICON => IconHelper::TAG],
                ])
                ->add('pec', EmailType::class, [
                    FormHelper::REQUIRED => false,
                    FormHelper::LABEL => 'registration.fields.pec',
                    FormHelper::ATTR => [
                        'class' => 'form-control',
                        FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                        FormHelper::ICON => IconHelper::EMAIL],
                    FormHelper::CONSTRAINTS => [
                        new Email([
                            'mode' => 'html5',
                            'message' => "pec.notValid",
                        ]),
                    ],
                ])
                ->add('uniqueCode', TextType::class, [
                    FormHelper::REQUIRED => false,
                    FormHelper::LABEL => 'registration.fields.uniqueCode',
                    FormHelper::ATTR => [
                        'class' => 'form-control',
                        FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                        FormHelper::ICON => IconHelper::TAG,
                    ],
                ]);
        }
        $builder->add('phone', TextType::class, [
            FormHelper::LABEL => 'registration.fields.phone',
            //FormHelper::REQUIRED => false,
            FormHelper::ATTR => [
                'class' => 'form-control',
                FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                FormHelper::ICON => IconHelper::PHONE],
            FormHelper::CONSTRAINTS => [
                new NotBlank(),
                new Length([
                    'max' => 15,
                    'maxMessage' => 'phone.length',
                ]),
            ],
        ])

            ->add('address', TextType::class, [
                FormHelper::LABEL => 'registration.fields.address',
                //FormHelper::REQUIRED => false,
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                    FormHelper::ICON => IconHelper::MARKER],
                FormHelper::CONSTRAINTS => [
                    new NotBlank(),
                ],
            ])
            ->add('city', TextType::class, [
                FormHelper::LABEL => 'registration.fields.city',
                //FormHelper::REQUIRED => false,
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                    FormHelper::ICON => IconHelper::MARKER],
                FormHelper::CONSTRAINTS => [
                    new NotBlank(),
                ],
            ])
            ->add('zipCode', TextType::class, [
                FormHelper::LABEL => 'registration.fields.zipCode',
                //FormHelper::REQUIRED => false,
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                    FormHelper::ICON => IconHelper::MARKER],
                FormHelper::CONSTRAINTS => [
                    new NotBlank(),
                ],
            ]);

        $builder->add('privacyFlags', CollectionType::class, [
            FormHelper::LABEL => false,
            'entry_type' => PrivacyType::class,
            FormHelper::ATTR => [
                FormHelper::RESPONSIVE => 'col-md-12',
            ],
        ]);
        if ($options[self::HAS_AGREE_TERMS]) {
            $builder->add('agreeTerms', CheckboxType::class, [
                FormHelper::LABEL => 'readFullPolicy',
                'mapped' => false,
                FormHelper::ATTR => [
                    FormHelper::RESPONSIVE => 'col-md-12 ml-3 text-center',
                    'class' => 'agreeTerms',
                ],
            ]);
        }
        $builder->add('captcha', CaptchaType::class, [
            FormHelper::LABEL => false,
            'mapped' => false,
        ])->add('submit', SubmitType::class, [
            FormHelper::LABEL => 'signUp',
            FormHelper::ATTR => ['class' => 'btn btnCenter btnSecondary',
                FormHelper::RESPONSIVE => 'col-md-12'],
        ]);

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars[self::BUSINESS] = $options[self::BUSINESS];
        $view->vars[self::AGENCY] = $options[self::AGENCY];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            self::HAS_AGREE_TERMS => false,
            FormHelper::LOCALE => 'it',
            // FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
            FormHelper::CSRF_PROTECTION => true,
            FormHelper::CSRF_NAME => 'csfr_registration_token',
            FormHelper::CSRF_TOKEN_ID => 'registration_token_90ab5',
            self::BUSINESS => false,
            self::AGENCY => false,
            self::PLUGIN => false,
            self::PRIVACY_READ_TEXT => 'read',
        ]);
    }
}