<?php

    namespace NeoxNotify\NeoxNotifyBundle\Form;
    
	use NeoxNotify\NeoxNotifyBundle\Model\Email;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class EmailModelType extends AbstractType
    {
        public function buildForm( FormBuilderInterface $builder, array $options ) : void {
            $ajax = $options["option"]["ajax"] ?? false;

            $builder
                //                ->add( 'recipient', EmailType::class, array(
                //                    'attr'               => array(
                //                        'placeholder' => 'email.form.recipient.placeholder',
                //                    ),
                //                    'label_attr'         => [ 'class' => 'col-sm-10' ],
                //                    'label'              => 'email.form.recipient.label',
                //                    'translation_domain' => 'email',
                //                    'required'           => true,
                //                ) )
                ->add( 'sender', EmailType::class, array(
                    'disabled'           => false,
                    "attr"               => array(
                        "placeholder" => "email.form.sender.placeholder",
                        'class'       => 'requirede text-whitee email',
                    ),
                    'label'              => 'email.form.sender.label',
                    'translation_domain' => 'email',
//                    'required'           => true,
                    //                    'label_attr' => ['class' => 'required email form-control'],
                ) )
                ->add( 'senderName', TextType::class, array(
                    'disabled'           => false,
                    "attr"               => array(
                        "placeholder" => "email.form.name.placeholder",
                        'class'       => 'required text-whitee form-control',
                    ),
                    'label'              => 'email.form.name.label',
                    'translation_domain' => 'email',
                    'label_attr'         => [ 'class' => 'col-sm-10' ],
                    'required'           => true,
                ) )
                ->add( 'subject', TextType::class, array(
                        'disabled'           => false,
                        "attr"               => array(
                            "placeholder" => "email.form.subject.placeholder",
                            'class'       => 'required text-whitee form-control',
                        ),
                        'label'              => 'email.form.subject.label',
                        'translation_domain' => 'email',
                        'label_attr'         => [ 'class' => 'col-sm-10' ],
                        'required'           => true,
                    ) )
                ->add( 'message', TextareaType::class, array(
                        'disabled'           => false,
                        "attr"               => array(
                            "placeholder" => "email.form.message.placeholder",
                            'class'       => 'required text-whitee form-control',
                            'rows'        => "7",
                            'cols'        => '30',
                        ),
                        'label'              => 'email.form.message.label',
                        'translation_domain' => 'email',
                        'label_attr'         => [ 'class' => 'col-sm-10' ],
                        'required'           => true,
                    ) );
        }

        public function configureOptions( OptionsResolver $resolver ) : void {
            $resolver->setDefaults( [
                // Configure your form options here
                'data_class' => Email::class,
                'option' => null,
            ] );
        }
    }