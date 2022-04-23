<?php

namespace App\Form;

use App\Services\Helpers\FormHelper;
use App\Services\Helpers\IconHelper;
use App\Services\Helpers\TranslationHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordRequestFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
                FormHelper::HELP => $this->translator->trans("resetPassInfo", [], 'security')
            ])
            ->add('submit', SubmitType::class, [
                FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
                FormHelper::LABEL => 'resetPassBtn',
                FormHelper::ATTR => ['class' => 'btn btnCenter btnInfo buttonMargin',
                    FormHelper::RESPONSIVE => 'cl-both w-100 pt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}