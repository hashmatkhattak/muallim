<div class="row">
    <table class="table">
        <tr style="padding: 1px">
            @foreach($week_days as $key=>$slots)
                <td style="padding-left:0px">
                    <ul style="width: 143px;margin-right:4px !important;padding: 0 !important;">
                        <li style="text-align: center"><b>{{ $key }}</b></li>
                        @foreach($slots as $slot)
                            <li style="background: goldenrod">{{ $slot->t_slot }}</li>
                        @endforeach
                    </ul>
                </td>
            @endforeach
        </tr>
    </table>
</div>
