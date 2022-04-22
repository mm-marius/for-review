<?php

namespace App\Form;

use App\Entity\User;
use App\Services\Helpers\FormHelper;
use App\Services\Helpers\IconHelper;
use App\Services\Helpers\TranslationHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationForm extends AbstractType
{
    const HAS_AGREE_TERMS = 'hasAgreeTerms';
    const PRIVACY_READ_TEXT = 'privacyReadText';

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('email', EmailType::class, [
                FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
                FormHelper::LABEL => 'fields.email',
                FormHelper::ATTR => [
                    'autocomplete' => 'off',
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-12',
                    FormHelper::ICON => IconHelper::EMAIL],
                FormHelper::CONSTRAINTS => [
                    new NotBlank([
                        'message' => $this->translator->trans("fields.emailEmpty", [], 'security'),
                    ]),
                    new Email([
                        'mode' => 'html5',
                        'message' => $this->translator->trans("fields.emailNotValid", [], 'security'),
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
                'invalid_message' => 'errors.password.invalid_message',
                'options' => [
                    'attr' => [
                        'class' => 'password-field form-control',
                        FormHelper::RESPONSIVE => 'col-md-12 col-lg-12',
                        FormHelper::ICON => IconHelper::PASSWORD,
                    ],
                ],
                'required' => true,
                'first_options' => ['label' => 'fields.password'],
                'second_options' => ['label' => 'fields.repeat_password'],
                FormHelper::ROW_ATTR => [
                    'class' => 'col-md-12 col-lg-12'],
                'mapped' => false,
                FormHelper::CONSTRAINTS => [
                    new NotBlank([
                        'message' => $this->translator->trans('fields.passwordEmpty', [], 'security'),
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => $this->translator->trans('fields.passwordLength', [], 'security'),
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('vatCode', TextType::class, [
                FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
                FormHelper::LABEL => 'fields.vatCode',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-12',
                    FormHelper::ICON => IconHelper::BUSINESS],
                FormHelper::CONSTRAINTS => [
                    new NotBlank(),
                ],
            ])
            ->add('businessName', TextType::class, [
                FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
                FormHelper::LABEL => 'fields.businessName',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-12',
                    FormHelper::ICON => IconHelper::BUSINESS],
                FormHelper::CONSTRAINTS => [
                    new NotBlank(),
                ],
            ])
            ->add('phone', TextType::class, [
                FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
                FormHelper::LABEL => 'fields.phone',
                FormHelper::REQUIRED => true,
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-12',
                    FormHelper::ICON => IconHelper::PHONE],
                FormHelper::CONSTRAINTS => [
                    new NotBlank(),
                    new Length([
                        'max' => 15,
                        'maxMessage' => 'phone.length',
                    ]),
                ],
            ]);

        if ($options[self::HAS_AGREE_TERMS]) {
            $builder->add('agreeTerms', CheckboxType::class, [
                FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
                FormHelper::LABEL => 'fields.acceptPolicy',
                FormHelper::ATTR => [
                    FormHelper::RESPONSIVE => 'col-md-12 ml-3 text-center',
                    'class' => 'agreeTerms',
                ],
                FormHelper::CONSTRAINTS => [
                    new IsTrue([
                        "message" => $this->translator->trans('fields.agreeTerms', [], 'security')
                    ])
                ],
            ]);
        }
        $builder->add('submit', SubmitType::class, [
            FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
            FormHelper::LABEL => 'signUp.button',
            FormHelper::ATTR => ['class' => 'btn btnCenter btnInfo buttonMargin',
                FormHelper::RESPONSIVE => 'cl-both w-100 pt-3'],
        ]);

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // $view->vars[self::BUSINESS] = $options[self::BUSINESS];
        // $view->vars[self::AGENCY] = $options[self::AGENCY];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            self::HAS_AGREE_TERMS => false,
            FormHelper::LOCALE => 'it',
            FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
            FormHelper::CSRF_PROTECTION => true,
            FormHelper::CSRF_NAME => 'csfr_registration_token',
            FormHelper::CSRF_TOKEN_ID => 'registration_token_90ab5',
            self::PRIVACY_READ_TEXT => 'fields.readFullPolicy',
        ]);
    }
}