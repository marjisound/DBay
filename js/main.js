$('#postcode_lookup').getAddress({
    api_key: 'YOUR_API_KEY', 
    output_fields:{
        line_1: '#line1',
        line_2: '#line2',
        line_3: '#line3',
        post_town: '#town',
        county: '#county',
        postcode: '#postcode'
    },
<!--  Optionally register callbacks at specific stages -->                                                                                                               
    onLookupSuccess: function(data){/* Your custom code */},
    onLookupError: function(){/* Your custom code */},
    onAddressSelected: function(elem,index){/* Your custom code */}
});