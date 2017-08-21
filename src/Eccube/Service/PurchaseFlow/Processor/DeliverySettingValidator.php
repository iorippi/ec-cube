<?php

namespace Eccube\Service\PurchaseFlow\Processor;

use Eccube\Entity\ItemInterface;
use Eccube\Repository\DeliveryRepository;
use Eccube\Service\PurchaseFlow\ItemValidateException;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\ValidatableItemProcessor;

/**
 * 商品種別に配送業者が設定されているかどうか.
 */
class DeliverySettingValidator extends ValidatableItemProcessor
{
    /**
     * @var DeliveryRepository
     */
    protected $deliveryRepository;

    public function __construct(DeliveryRepository $deliveryRepository)
    {
        $this->deliveryRepository = $deliveryRepository;
    }

    protected function validate(ItemInterface $item, PurchaseContext $context)
    {
        if (!$item->isProduct()) {
            return;
        }

        $ProductType = $item->getProductClass()->getProductType();
        $Deliveries = $this->deliveryRepository->findBy(['ProductType' => $ProductType]);

        if (empty($Deliveries)) {
            throw ItemValidateException::fromProductClass('cart.product.not.producttype', $item->getProductClass());
        }
    }

    protected function handle(ItemInterface $item, PurchaseContext $context)
    {
        $item->setQuantity(0);
    }
}
