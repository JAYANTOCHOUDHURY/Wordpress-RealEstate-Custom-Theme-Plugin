<?php
class Mortgage_Calculator_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'mortgage_calculator_widget',
            __('Mortgage Calculator', 'property-enhancer'),
            ['description' => __('Calculates EMI based on loan details.', 'property-enhancer')]
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
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
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance) {
        return $new_instance;
    }
}

