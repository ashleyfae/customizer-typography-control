<# if ( data.tooltip ) { #>
    <a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='dashicons dashicons-info'></span></a>
<# } #>

<label class="customizer-text">
    <# if ( data.label ) { #>
        <span class="customize-control-title">{{{ data.label }}}</span>
    <# } #>
    <# if ( data.description ) { #>
        <span class="description customize-control-description">{{{ data.description }}}</span>
    <# } #>
</label>

<div class="wrapper">
    <# if ( data.default['font-family'] ) { #>
        <# if ( '' == data.value['font-family'] ) { data.value['font-family'] = data.default['font-family']; } #>
        <# if ( data.choices['fonts'] ) { data.fonts = data.choices['fonts']; } #>
        <div class="font-family">
            <h5>{{ data.l10n['font-family'] }}</h5>
            <select id="ng-typography-font-family-{{{ data.id }}}" placeholder="{{ data.l10n['select-font-family'] }}"></select>
        </div>
        <div class="variant ng-variant-wrapper">
            <h5>{{ data.l10n['style'] }}</h5>
            <select class="variant" id="ng-typography-variant-{{{ data.id }}}"></select>
        </div>
    <# } #>

    <# if ( data.default['font-size'] ) { #>
        <div class="font-size">
            <h5>{{ data.l10n['font-size'] }}</h5>
            <input type="text" value="{{ data.value['font-size'] }}"/>
        </div>
    <# } #>

    <# if ( data.default['line-height'] ) { #>
        <div class="line-height">
            <h5>{{ data.l10n['line-height'] }}</h5>
            <input type="text" value="{{ data.value['line-height'] }}"/>
        </div>
    <# } #>

    <# if ( data.default['letter-spacing'] ) { #>
        <div class="letter-spacing">
            <h5>{{ data.l10n['letter-spacing'] }}</h5>
            <input type="text" value="{{ data.value['letter-spacing'] }}"/>
        </div>
    <# } #>

    <# if ( data.default['text-align'] ) { #>
        <div class="text-align">
            <h5>{{ data.l10n['text-align'] }}</h5>
            <select>
                <option value="left"<# if ( data.value['text-align'] == 'left' ) { #> selected="selected" <# } #>>{{ data.l10n['left'] }}</option>
                <option value="right"<# if ( data.value['text-align'] == 'right' ) { #> selected="selected"<# } #>>{{ data.l10n['right'] }}</option>
                <option value="center"<# if ( data.value['text-align'] == 'center' ) { #> selected="selected"<# } #>>{{ data.l10n['center'] }}</option>
                <option value="justify"<# if ( data.value['text-align'] == 'justify' ) { #> selected="selected"<# } #>>{{ data.l10n['justify'] }}</option>
            </select>
        </div>
    <# } #>

    <# if ( data.default['text-transform'] ) { #>
        <div class="text-transform">
            <h5>{{ data.l10n['text-transform'] }}</h5>
            <select>
                <option value="none"<# if ( data.value['text-transform'] == 'none' ) { #> selected="selected" <# } #>>{{ data.l10n['none'] }}</option>
                <option value="lowercase"<# if ( data.value['text-transform'] == 'lowercase' ) { #> selected="selected"<# } #>>{{ data.l10n['lowercase'] }}</option>
                <option value="uppercase"<# if ( data.value['text-transform'] == 'uppercase' ) { #> selected="selected"<# } #>>{{ data.l10n['uppercase'] }}</option>
            </select>
        </div>
    <# } #>

    <# if ( data.default['color'] ) { #>
        <div class="color">
            <h5>{{ data.l10n['color'] }}</h5>
            <input type="text" data-palette="{{ data.palette }}" data-default-color="{{ data.default['color'] }}" value="{{ data.value['color'] }}" class="ng-color-control color-picker" {{{ data.link }}} />
        </div>
    <# } #>
</div>