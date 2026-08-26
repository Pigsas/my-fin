<?php

namespace App\Twig;

use App\Entity\Invoice;
use App\Enum\UnitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;


#[AsLiveComponent('invoice_form_type', template: 'components/InvoiceFormType.html.twig')]
class InvoiceFormType extends AbstractController
{
    use LiveCollectionTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public Invoice $initialFormData;

    #[LiveProp]
    public bool $checkError;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(\App\Form\InvoiceFormType::class, $this->initialFormData);
    }

    #[PostMount]
    public function submitFormStart(): void
    {
        if ($this->checkError) {
            $this->formValues =  $this->extractFormValues($this->getFormView());
            $form = $this->getForm();
            $form->submit($this->formValues);
            $this->formView = null;
        }
    }

    #[PreReRender]
    public function clear() {
        $this->formView = null;
    }

    #[LiveAction]
    public function addCollectionItem(
        PropertyAccessorInterface $propertyAccessor,
        #[LiveArg] string $name
    ): void {
        $propertyPath = $this->fieldNameToPropertyPath(
            $name,
            $this->formName
        );

        $data = $propertyAccessor->getValue(
            $this->formValues,
            $propertyPath
        );

        if (!is_array($data)) {
            $propertyAccessor->setValue(
                $this->formValues,
                $propertyPath,
                []
            );

            $data = [];
        }

        $index = [] !== $data
            ? max(array_keys($data)) + 1
            : 0;

        $propertyAccessor->setValue(
            $this->formValues,
            $propertyPath . "[$index]",
            [
                'service' => '',
                'name' => '',
                'unit' => UnitType::PCS->value,
                'amount' => 1,
                'price' => 0,
                'discount' => 0,
                'total' => 0,
            ]
        );
    }

    private function fieldNameToPropertyPath(
        string $collectionFieldName,
        string $rootFormName
    ): string {
        $propertyPath = $collectionFieldName;

        if (str_starts_with($collectionFieldName, $rootFormName)) {
            $propertyPath = substr_replace(
                $collectionFieldName,
                '',
                0,
                mb_strlen($rootFormName)
            );
        }

        if (!str_starts_with($propertyPath, '[')) {
            $propertyPath = "[$propertyPath]";
        }

        return $propertyPath;
    }
}
