const fs = require('fs');

const projects = [];
const projectNames = [
    "Lodha Altamount", "Godrej Emerald", "Prestige Falcon City", "Sobha Dream Acres", "DLF The Camellias",
    "Piramal Mahalaxmi", "Runwal Bliss", "Rustomjee Seasons", "Purva Zenium", "Brigade Exotica",
    "Salarpuria Sattva", "L&T Crescent Bay", "Hiranandani Estate", "Mahindra Roots", "Tata Promont",
    "Raheja Vistas", "Sunteck City", "Kalpataru Radiance", "Wadhwa Courtyard", "Shapoorji Pallonji Parkwest"
];

const locations = [
    "Altamount Road, Mumbai", "Thane West, Mumbai", "Kanakapura Road, Bangalore", "Panathur, Bangalore", "Golf Course Road, Gurgaon",
    "Mahalaxmi, Mumbai", "Kanjurmarg East, Mumbai", "Bandra East, Mumbai", "Hosahalli, Bangalore", "Old Madras Road, Bangalore",
    "Magadi Road, Bangalore", "Parel, Mumbai", "Thane West, Mumbai", "Kandivali East, Mumbai", "Banashankari, Bangalore",
    "Andheri East, Mumbai", "Goregaon West, Mumbai", "Goregaon West, Mumbai", "Pokhran Road 2, Thane", "Binnypete, Bangalore"
];

const bannerImages = [
    "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075&auto=format&fit=crop",
    "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=2070&auto=format&fit=crop",
    "https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?q=80&w=2070&auto=format&fit=crop",
    "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=2070&auto=format&fit=crop",
    "https://images.unsplash.com/photo-1613490900233-141c536eb3b8?q=80&w=1974&auto=format&fit=crop"
];

const interiorImages = [
    "https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=1974&auto=format&fit=crop",
    "https://images.unsplash.com/photo-1600607686527-6fb886090705?q=80&w=1974&auto=format&fit=crop",
    "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?q=80&w=2070&auto=format&fit=crop"
];

function getRandom(arr) {
    return arr[Math.floor(Math.random() * arr.length)];
}

let sql = "INSERT INTO projects (builder_id, city_id, name, slug, type, status, price_min, price_max, price_on_request, unit_types, area_range, rera_id, rera_verified, address, location_area, short_description, description, banner_image, thumbnail_image, video_url, possession_date, is_featured, sort_order, meta_title, meta_description, project_logo, contact_phone, whatsapp_number, virtual_tour_url, rera_qr_code, connectivity, highlights, marquee_text, gallery_images, interior_images, exterior_images) VALUES\\n";

for(let i=0; i<20; i++) {
    const name = projectNames[i];
    const slug = name.toLowerCase().replace(/[^a-z0-9]/g, '-') + '-' + Math.floor(Math.random()*1000);
    const loc = locations[i];
    const banner = getRandom(bannerImages);
    const interiors = JSON.stringify([getRandom(interiorImages), getRandom(interiorImages), getRandom(interiorImages)]);
    const exteriors = JSON.stringify([getRandom(bannerImages), getRandom(bannerImages)]);
    const priceMin = (Math.random() * 5 + 1).toFixed(2);
    const priceMax = (parseFloat(priceMin) + Math.random() * 5).toFixed(2);
    
    const connectivity = JSON.stringify({
        "Airport": "International Airport\\nLocal Airport",
        "Hospitals": "City Care Hospital\\nMetro Health Center",
        "Schools": "International School\\nGlobal Academy",
        "Malls": "Nexus Mall\\nCity Center"
    });
    
    const highlights = "Premium Luxury Living\\nState of the art clubhouse\\nOlympic size swimming pool\\nLush green landscapes\\n24/7 Security and Concierge";
    
    const description = "<p>Welcome to " + name + ", an ultra-premium residential project located in " + loc + ". Experience the epitome of luxury with world-class amenities and breathtaking views.</p>";

    sql += "(1, 1, '" + name + "', '" + slug + "', 'residential', 'under_construction', " + priceMin + ", " + priceMax + ", 0, '2, 3, 4 BHK', '1200 - 3500 Sq.Ft.', 'P123456789" + i + "', 1, '" + loc + "', '" + loc + "', 'Luxury apartments in " + loc + ".', '" + description + "', '" + banner + "', '" + banner + "', 'https://www.youtube.com/embed/tgbNymZ7vqY', 'Dec 2026', 1, " + i + ", '" + name + " | Luxury Homes', 'Buy " + name + " luxury homes in " + loc + ".', 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=200&h=200&fit=crop', '+91 9999999999', '+91 9999999999', 'https://www.youtube.com/embed/tgbNymZ7vqY', 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=RERA123', '" + connectivity + "', '" + highlights + "', 'Special Offer: No EMI till Possession!', '" + exteriors + "', '" + interiors + "', '" + exteriors + "')";
    
    if(i === 19) sql += ";\\n";
    else sql += ",\\n";
}

fs.writeFileSync('database/migrations/012_add_20_projects.sql', sql);
console.log('Migration generated.');
