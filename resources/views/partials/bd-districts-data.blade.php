<script>
const bdDistricts = {
  "Dhaka":            { division: "Dhaka",        upazilas: ["Adabor","Badda","Cantonment","Dakshinkhan","Demra","Dhanmondi","Dohar","Hazaribagh","Kafrul","Kalabagan","Keraniganj","Khilgaon","Khilkhet","Kotwali","Lalbagh","Mirpur","Mohammadpur","Motijheel","Nawabganj","Pallabi","Ramna","Savar","Sabujbagh","Shah Ali","Shyampur","Sutrapur","Tejgaon","Turag","Uttara","Wari"] },
  "Faridpur":         { division: "Dhaka",        upazilas: ["Alfadanga","Bhanga","Boalmari","Charbhadrasan","Faridpur Sadar","Madhukhali","Nagarkanda","Sadarpur","Saltha"] },
  "Gazipur":          { division: "Dhaka",        upazilas: ["Gazipur Sadar","Kaliakair","Kaliganj","Kapasia","Sreepur"] },
  "Gopalganj":        { division: "Dhaka",        upazilas: ["Gopalganj Sadar","Kashiani","Kotalipara","Muksudpur","Tungipara"] },
  "Kishoreganj":      { division: "Dhaka",        upazilas: ["Austagram","Bajitpur","Bhairab","Hossainpur","Itna","Karimganj","Katiadi","Kishoreganj Sadar","Kuliarchar","Mithamain","Nikli","Pakundia","Tarail"] },
  "Madaripur":        { division: "Dhaka",        upazilas: ["Kalkini","Madaripur Sadar","Rajoir","Shibchar"] },
  "Manikganj":        { division: "Dhaka",        upazilas: ["Daulatpur","Ghior","Harirampur","Manikganj Sadar","Saturia","Shivalaya","Singair"] },
  "Munshiganj":       { division: "Dhaka",        upazilas: ["Gazaria","Lohajang","Munshiganj Sadar","Sirajdikhan","Sreenagar","Tongibari"] },
  "Narayanganj":      { division: "Dhaka",        upazilas: ["Araihazar","Bandar","Narayanganj Sadar","Rupganj","Sonargaon"] },
  "Narsingdi":        { division: "Dhaka",        upazilas: ["Belabo","Monohardi","Narsingdi Sadar","Palash","Raipura","Shibpur"] },
  "Rajbari":          { division: "Dhaka",        upazilas: ["Baliakandi","Goalandaghat","Kalukhali","Pangsha","Rajbari Sadar"] },
  "Shariatpur":       { division: "Dhaka",        upazilas: ["Bhedarganj","Damudya","Gosairhat","Naria","Shariatpur Sadar","Zajira"] },
  "Tangail":          { division: "Dhaka",        upazilas: ["Basail","Bhuapur","Delduar","Dhanbari","Ghatail","Gopalpur","Kalihati","Madhupur","Mirzapur","Nagarpur","Sakhipur","Tangail Sadar"] },
  "Bandarban":        { division: "Chattogram",   upazilas: ["Ali Kadam","Bandarban Sadar","Lama","Naikhongchhari","Rowangchhari","Ruma","Thanchi"] },
  "Brahmanbaria":     { division: "Chattogram",   upazilas: ["Akhaura","Ashuganj","Bancharampur","Bijoynagar","Brahmanbaria Sadar","Kasba","Nabinagar","Nasirnagar","Sarail"] },
  "Chandpur":         { division: "Chattogram",   upazilas: ["Chandpur Sadar","Faridganj","Haimchar","Haziganj","Kachua","Matlab Dakshin","Matlab Uttar","Shahrasti"] },
  "Chattogram":       { division: "Chattogram",   upazilas: ["Anwara","Bakalia","Banshkhali","Boalkhali","Chandgaon","Double Mooring","Fatikchhari","Hathazari","Karnaphuli","Khulshi","Kotwali","Lohagara","Mirsharai","Pahartali","Panchlaish","Patenga","Patiya","Rangunia","Raozan","Sandwip","Satkania","Sitakunda"] },
  "Cumilla":          { division: "Chattogram",   upazilas: ["Barura","Brahmanpara","Burichang","Chandina","Chauddagram","Cumilla Sadar","Daudkandi","Debidwar","Homna","Laksam","Lalmai","Manoharganj","Meghna","Muradnagar","Nangalkot","Titas"] },
  "Cox's Bazar":      { division: "Chattogram",   upazilas: ["Chakaria","Cox's Bazar Sadar","Kutubdia","Maheshkhali","Pekua","Ramu","Teknaf","Ukhia"] },
  "Feni":             { division: "Chattogram",   upazilas: ["Chhagalnaiya","Daganbhuiyan","Feni Sadar","Fulgazi","Parshuram","Sonagazi"] },
  "Khagrachhari":     { division: "Chattogram",   upazilas: ["Dighinala","Guimara","Khagrachhari Sadar","Lakshmichhari","Mahalchhari","Manikchhari","Matiranga","Panchhari","Ramgarh"] },
  "Lakshmipur":       { division: "Chattogram",   upazilas: ["Kamalnagar","Lakshmipur Sadar","Ramganj","Ramgati","Roypur"] },
  "Noakhali":         { division: "Chattogram",   upazilas: ["Begumganj","Chatkhil","Companiganj","Hatiya","Kabirhat","Noakhali Sadar","Senbagh","Sonaimuri","Subarnachar"] },
  "Rangamati":        { division: "Chattogram",   upazilas: ["Bagaichhari","Barkal","Belaichhari","Jurachhari","Kaptai","Kaukhali","Langadu","Nannerchar","Rajasthali","Rangamati Sadar"] },
  "Bogura":           { division: "Rajshahi",     upazilas: ["Adamdighi","Bogura Sadar","Dhunat","Dhupchanchia","Gabtali","Kahaloo","Nandigram","Sariakandi","Shajahanpur","Sherpur","Shibganj","Sonatala"] },
  "Chapai Nawabganj": { division: "Rajshahi",     upazilas: ["Bholahat","Chapai Nawabganj Sadar","Gomastapur","Nachole","Shibganj"] },
  "Joypurhat":        { division: "Rajshahi",     upazilas: ["Akkelpur","Joypurhat Sadar","Kalai","Khetlal","Panchbibi"] },
  "Naogaon":          { division: "Rajshahi",     upazilas: ["Atrai","Badalgachhi","Dhamoirhat","Manda","Mahadebpur","Naogaon Sadar","Niamatpur","Patnitala","Porsha","Raninagar","Sapahar"] },
  "Natore":           { division: "Rajshahi",     upazilas: ["Bagatipara","Baraigram","Gurudaspur","Lalpur","Natore Sadar","Singra"] },
  "Pabna":            { division: "Rajshahi",     upazilas: ["Atgharia","Bera","Bhangura","Chatmohar","Faridpur","Ishwardi","Pabna Sadar","Santhia","Sujanagar"] },
  "Rajshahi":         { division: "Rajshahi",     upazilas: ["Bagha","Bagmara","Boalia","Charghat","Durgapur","Godagari","Matihar","Mohanpur","Paba","Puthia","Rajpara","Shah Makhdum","Tanore"] },
  "Sirajganj":        { division: "Rajshahi",     upazilas: ["Belkuchi","Chauhali","Kamarkhanda","Kazipur","Raiganj","Shahjadpur","Sirajganj Sadar","Tarash","Ullahpara"] },
  "Bagerhat":         { division: "Khulna",       upazilas: ["Bagerhat Sadar","Chitalmari","Fakirhat","Kachua","Mollahat","Mongla","Morrelganj","Rampal","Sarankhola"] },
  "Chuadanga":        { division: "Khulna",       upazilas: ["Alamdanga","Chuadanga Sadar","Damurhuda","Jibannagar"] },
  "Jashore":          { division: "Khulna",       upazilas: ["Abhaynagar","Bagherpara","Chaugachha","Jashore Sadar","Jhikargachha","Keshabpur","Manirampur","Sharsha"] },
  "Jhenaidah":        { division: "Khulna",       upazilas: ["Harinakunda","Jhenaidah Sadar","Kaliganj","Kotchandpur","Maheshpur","Shailkupa"] },
  "Khulna":           { division: "Khulna",       upazilas: ["Batiaghata","Dacope","Daulatpur","Dighalia","Dumuria","Fultala","Khalishpur","Khan Jahan Ali","Koyra","Paikgachha","Rupsa","Sonadanga","Terokhada"] },
  "Kushtia":          { division: "Khulna",       upazilas: ["Bheramara","Daulatpur","Khoksa","Kumarkhali","Kushtia Sadar","Mirpur"] },
  "Magura":           { division: "Khulna",       upazilas: ["Magura Sadar","Mohammadpur","Shalikha","Sreepur"] },
  "Meherpur":         { division: "Khulna",       upazilas: ["Gangni","Meherpur Sadar","Mujibnagar"] },
  "Narail":           { division: "Khulna",       upazilas: ["Kalia","Lohagara","Narail Sadar"] },
  "Satkhira":         { division: "Khulna",       upazilas: ["Assasuni","Debhata","Kalaroa","Kaliganj","Satkhira Sadar","Shyamnagar","Tala"] },
  "Barguna":          { division: "Barisal",      upazilas: ["Amtali","Bamna","Barguna Sadar","Betagi","Patharghata","Taltali"] },
  "Barishal":         { division: "Barisal",      upazilas: ["Agailjhara","Babuganj","Bakerganj","Banari Para","Barishal Sadar","Gaurnadi","Hizla","Mehendiganj","Muladi","Wazirpur"] },
  "Bhola":            { division: "Barisal",      upazilas: ["Bhola Sadar","Burhanuddin","Char Fasson","Daulatkhan","Lalmohan","Manpura","Tazumuddin"] },
  "Jhalokati":        { division: "Barisal",      upazilas: ["Jhalokati Sadar","Kathalia","Nalchity","Rajapur"] },
  "Patuakhali":       { division: "Barisal",      upazilas: ["Bauphal","Dashmina","Dumki","Galachipa","Kalapara","Mirzaganj","Patuakhali Sadar","Rangabali"] },
  "Pirojpur":         { division: "Barisal",      upazilas: ["Bhandaria","Kawkhali","Mathbaria","Nazirpur","Nesarabad","Pirojpur Sadar","Zianagar"] },
  "Habiganj":         { division: "Sylhet",       upazilas: ["Ajmiriganj","Bahubal","Baniyachong","Chunarughat","Habiganj Sadar","Lakhai","Madhabpur","Nabiganj"] },
  "Moulvibazar":      { division: "Sylhet",       upazilas: ["Barlekha","Juri","Kamalganj","Kulaura","Moulvibazar Sadar","Rajnagar","Sreemangal"] },
  "Sunamganj":        { division: "Sylhet",       upazilas: ["Bishwamvarpur","Chhatak","Derai","Dharampasha","Dowarabazar","Jagannathpur","Jamalganj","Sullah","Sunamganj Sadar","Tahirpur"] },
  "Sylhet":           { division: "Sylhet",       upazilas: ["Balaganj","Beanibazar","Bishwanath","Companiganj","Dakshin Surma","Fenchuganj","Golapganj","Gowainghat","Jaintiapur","Kanaighat","Osmani Nagar","Sylhet Sadar","Zakiganj"] },
  "Dinajpur":         { division: "Rangpur",      upazilas: ["Birampur","Birganj","Biral","Bochaganj","Chirirbandar","Dinajpur Sadar","Fulbari","Ghoraghat","Hakimpur","Kaharole","Khansama","Nawabganj","Parbatipur"] },
  "Gaibandha":        { division: "Rangpur",      upazilas: ["Fulchhari","Gaibandha Sadar","Gobindaganj","Palashbari","Sadullapur","Saghata","Sundarganj"] },
  "Kurigram":         { division: "Rangpur",      upazilas: ["Bhurungamari","Char Rajibpur","Chilmari","Kurigram Sadar","Nageshwari","Phulbari","Rajarhat","Rajibpur","Raumari","Ulipur"] },
  "Lalmonirhat":      { division: "Rangpur",      upazilas: ["Aditmari","Hatibandha","Kaliganj","Lalmonirhat Sadar","Patgram"] },
  "Nilphamari":       { division: "Rangpur",      upazilas: ["Dimla","Domar","Jaldhaka","Kishorganj","Nilphamari Sadar","Saidpur"] },
  "Panchagarh":       { division: "Rangpur",      upazilas: ["Atwari","Boda","Debiganj","Panchagarh Sadar","Tetulia"] },
  "Rangpur":          { division: "Rangpur",      upazilas: ["Badarganj","Gangachara","Kaunia","Mithapukur","Pirgachha","Pirganj","Rangpur Sadar","Taraganj"] },
  "Thakurgaon":       { division: "Rangpur",      upazilas: ["Baliadangi","Haripur","Pirganj","Ranisankail","Thakurgaon Sadar"] },
  "Jamalpur":         { division: "Mymensingh",   upazilas: ["Bakshiganj","Dewanganj","Islampur","Jamalpur Sadar","Madarganj","Melandaha","Sarishabari"] },
  "Mymensingh":       { division: "Mymensingh",   upazilas: ["Bhaluka","Dhobaura","Fulbaria","Gafargaon","Gauripur","Haluaghat","Ishwarganj","Muktagachha","Mymensingh Sadar","Nandail","Phulpur","Trishal"] },
  "Netrokona":        { division: "Mymensingh",   upazilas: ["Atpara","Barhatta","Durgapur","Kalmakanda","Kendua","Khaliajuri","Madan","Mohanganj","Netrokona Sadar","Purbadhala"] },
  "Sherpur":          { division: "Mymensingh",   upazilas: ["Jhenaigati","Nakla","Nalitabari","Sherpur Sadar","Sreebardi"] }
};

function initDistrictDropdown(opts) {
    var districtSel = document.getElementById(opts.districtId);
    var upazilaSel  = document.getElementById(opts.upazilaId);
    var divisionEl  = opts.divisionId ? document.getElementById(opts.divisionId) : null;
    var currentDistrict  = opts.currentDistrict  || '';
    var currentUpazila   = opts.currentUpazila   || '';

    // Populate district options
    Object.keys(bdDistricts).sort().forEach(function(d) {
        var opt = document.createElement('option');
        opt.value = d; opt.textContent = d;
        if (d === currentDistrict) opt.selected = true;
        districtSel.appendChild(opt);
    });

    function populateUpazilas(districtName, selectedUpazila) {
        upazilaSel.innerHTML = '<option value="">-- Select Upazila/Area --</option>';
        if (districtName && bdDistricts[districtName]) {
            bdDistricts[districtName].upazilas.forEach(function(u) {
                var opt = document.createElement('option');
                opt.value = u; opt.textContent = u;
                if (u === selectedUpazila) opt.selected = true;
                upazilaSel.appendChild(opt);
            });
            if (divisionEl) divisionEl.value = bdDistricts[districtName].division;
        } else {
            if (divisionEl) divisionEl.value = '';
        }
    }

    populateUpazilas(currentDistrict, currentUpazila);

    districtSel.addEventListener('change', function() {
        populateUpazilas(this.value, '');
    });
}
</script>
