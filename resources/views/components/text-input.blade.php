@props(['disabled' => false, 'error' => false])

<input {{ $disabled ? 'disabled' : '' }} 
       {!! $attributes->merge([
           'class' => 'form-input' . ($error ? ' border-red-500 focus:border-red-500 focus:ring-red-500' : '')
       ]) !!}>