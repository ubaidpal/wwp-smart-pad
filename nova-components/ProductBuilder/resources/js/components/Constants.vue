<script>
    const UNITS=[
        'imperial',
        'metric'
    ];
    const IMPERIAL=[
        {
            group:'Square Meter',options:[
                { name:"width_based_chart_mt", label:"Width Based Chart"},
                { name:"height_based_chart_mt",label:"Height Based Chart"},
                { name:"width_and_height_based_chart_mt",label:"Width & Height Based Chart"},
                { name:"square_meter_rate",label:"Square Meter Rate"}
            ]
        }
    ];

    const METRIC = [
        {
            group:'Square Feet',options:[
                { name:"width_based_chart_ft", label:"Width Based Chart (SQ FT)"},
                { name:"height_based_chart_ft",label:"Height Based Chart (SQ FT)"},
                { name:"width_and_height_based_chart_ft",label:"Width & Height Based Chart (SQ FT)"},
                { name:"square_feet_rate",label:"Square Feet Rate"}
            ]
        },
        {
            group:'Square Inche',options:[
                { name:"width_based_chart_in", label:"Width Based Chart (SQ IN)"},
                { name:"height_based_chart_in",label:"Height Based Chart (SQ IN)"},
                { name:"width_and_height_based_chart_in",label:"Width & Height Based Chart (SQ IN)"},
                { name:"square_inche_rate",label:"Square Inche Rate"}
            ]
        }
    ];
    export default Constants{
        data: () => ({
            UNITS : UNITS,
            IMPERIAL : IMPERIAL,
            METRIC : METRIC
        })

    }
</script>
