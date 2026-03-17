<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteInfo extends Model
{
    protected $primaryKey = 'info_id';
    protected $table = 'website_info';
    protected $connection = 'mysql2';

    public $timestamps = false;
    

    protected $fillable = [
        'info_id',
        'Customers_Numbers',
        'presentation_title',     
        'fr_presentation_title', 
        'presentation_text',     
        'fr_presentation_text',  
        'title2',
        'fr_title2',
        'text2',
        'fr_text2',
        'title3',
        'fr_title3',
        'text3',
        'fr_text3',
        'presentation_photo',     
        'presentation_photo2',
        'fr_terms_Conditions',
        'terms_Conditions',
        'Privacy_Policy',
        'fr_Privacy_Policy', 
        'site_mission',
        'site_mission_fr',
        'site_history_fr',
        'site_history', 
        'site_vision',
        'site_vision_fr',
        'site_value_fr',
        'site_value',
        'site_goals',
        'site_goal_fr',
        'subscription_policy',
        'fr_subscription_policy',
        'refund_policy',
        'fr_refund_policy',
        'cash_on_delivery_details',
        'fr_cash_on_delivery_details',
        'support_details',
        'fr_support_details',
        'shipping_details',
        'fr_shipping_details',
        'name',
        'phone',
        'email',
        'address',
        'maps_localisation',
        'footer_text',
        'fr_footer_text',
        'facebook_link',
        'tiktok_link',
        'youtube_link',
        'rss_link',
        'payment_type',
        'logo',
        'short_logo',
        'Opening_hours',
        'fr_opening_hours',
        'currency',
    ];


    public function getTOpeningHoursAttribute(): ?string
    {
        return app()->getLocale() === 'fr'
            ? $this->fr_Opening_hours
            : $this->Opening_hours;
    }
    // Cash on Delivery Details
    public function getTCashOnDeliveryDetailsAttribute(): ?string
    {
        return app()->getLocale() === 'fr'
            ? $this->fr_cash_on_delivery_details
            : $this->cash_on_delivery_details;
    }

    // Support Details
    public function getTSupportDetailsAttribute(): ?string
    {
        return app()->getLocale() === 'fr'
            ? $this->fr_support_details
            : $this->support_details;
    }

    // Shipping Details
    public function getTShippingDetailsAttribute(): ?string
    {
        return app()->getLocale() === 'fr'
            ? $this->fr_shipping_details
            : $this->shipping_details;
    }

    // Privacy Policy
    public function getTPrivacyPolicyAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->fr_Privacy_Policy : $this->Privacy_Policy;
    }

    // Subscription Policy
    public function getTSubscriptionPolicyAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->fr_subscription_policy : $this->subscription_policy;
    }

    // Refund Policy
    public function getTRefundPolicyAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->fr_refund_policy : $this->refund_policy;
    }

    // Site Mission
    public function getTSiteMissionAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->site_mission_fr : $this->site_mission;
    }

    // Site Vision
    public function getTSiteVisionAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->site_vision_fr : $this->site_vision;
    }

    public function getTSiteValueAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->site_value_fr : $this->site_value;
    }


    // Site Goals
    public function getTSiteGoalsAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->site_goal_fr : $this->site_goals;
    }


    public function getTTermsConditionsAttribute(): ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_terms_Conditions : $this->terms_Conditions;
    }

    public function getTSaleTextAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_sale_text : $this->sale_text;
    }

    public function getTReservationTextAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_reservation_text : $this->reservation_text;
    }

    public function getTInvoiceTextAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_invoice_text : $this->invoice_text;
    }

    public function getTReceiptTextAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_receipt_text : $this->receipt_text;
    }

    public function getTBlTextAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_bl_text : $this->bl_text;
    }

    public function getTSiteHistoryAttribute() : ?string
    {
        return app()->getLocale() == 'fr' ? $this->site_history_fr : $this->site_history;
    }

    public function getTPresentationTitleAttribute(): ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_presentation_title : $this->presentation_title;
    }

    public function getTPresentationTextAttribute(): ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_presentation_text : $this->presentation_text;
    }

    // --- Présentation 2 ---
    public function getTTitle2Attribute(): ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_title2 : $this->title2;
    }

    public function getTText2Attribute(): ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_text2 : $this->text2;
    }

    // --- Présentation 3 ---
    public function getTTitle3Attribute(): ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_title3 : $this->title3;
    }

    public function getTText3Attribute(): ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_text3 : $this->text3;
    }

    public function getFPresentationPhotoAttribute(): ?string
    {
        return $this->presentation_photo ? 'data:image/jpeg;base64,'.base64_encode($this->presentation_photo) : '';
    }

    public function getFPresentationPhoto2Attribute(): ?string
    {
        return $this->presentation_photo2 ? 'data:image/jpeg;base64,'.base64_encode($this->presentation_photo2) : '';
    }


}