<?php
namespace PE_Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Mortgage_Calculator_Widget extends Widget_Base {

    public function get_name() {
        return 'mortgage_calculator';
    }

    public function get_title() {
        return __('Mortgage Calculator', 'property-enhancer');
    }

    public function get_icon() {
        return 'eicon-number-field'; // ✅ This icon is known to always show in Elementor
    }


    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        // No user-configurable controls needed
    }

    protected function render() {
        ?>
        <div class="mortgage-calculator-widget">
            <h4>Mortgage Calculator</h4>
            <form id="mortgage-form">
                <input type="number" id="loan_amount" placeholder="Loan Amount (₹)" required>
                <input type="number" id="interest_rate" placeholder="Interest Rate (%)" step="0.1" required>
                <input type="number" id="loan_term" placeholder="Loan Term (years)" required>
                <button type="submit">Calculate EMI</button>
            </form>
            <div id="emi_result" style="margin-top:10px; font-weight:bold;"></div>
        </div>
        <?php
    }
}
