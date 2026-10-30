<?php
/**
 * Content schema + default values for the Pasora homepage.
 *
 * This single file drives three things:
 *   1. the default text shown on a fresh install,
 *   2. the live values (merged with admin/content.local.json), and
 *   3. the fields rendered in the /admin Content editor.
 *
 * Each field: 'key' => [ 'Label', 'type', 'default' ]
 *   type: text | textarea | list | image
 */

return [

    'seo' => ['label' => 'Branding & SEO', 'fields' => [
        'site_title'       => ['Browser/SEO title', 'text', 'Pasora Solution Ltd | Software, Cloud & Cyber Security'],
        'site_description' => ['Meta description', 'textarea', "Full-service IT company in Accra: software development, cloud solutions, cyber security, IT consulting and 24/7 support. Anything IT, we've got you covered."],
        'org_name'         => ['Organisation name (schema)', 'text', 'Pasora Solution Ltd'],
        'slogan'           => ['Slogan (schema)', 'text', "Anything IT, We've Got You Covered."],
        'brand_name'       => ['Brand name', 'text', 'PASORA'],
        'brand_sub'        => ['Brand sub-text', 'text', 'SOLUTION LTD'],
        'logo'             => ['Logo image', 'image', 'assets/logo.svg'],
    ]],

    'hero' => ['label' => 'Hero', 'fields' => [
        'hero_kicker'  => ['Kicker', 'text', 'Pasora Solution Ltd'],
        'hero_title_1' => ['Headline (line 1)', 'text', 'Anything IT,'],
        'hero_title_2' => ['Headline (highlighted)', 'text', "We've Got You Covered."],
        'hero_lead'    => ['Lead paragraph', 'textarea', 'We design, build and protect the technology that powers your business, from custom software and cloud infrastructure to cyber security and round-the-clock support.'],
        'hero_btn1'    => ['Primary button', 'text', 'Explore Our Services'],
        'hero_btn2'    => ['Secondary button', 'text', 'Talk to an Expert'],
        'hero_badge1'  => ['Badge 1', 'text', 'Trusted IT Partner'],
        'hero_badge2'  => ['Badge 2', 'text', '24/7 Support'],
        'hero_badge3'  => ['Badge 3', 'text', 'Certified Engineers'],
    ]],

    'marquee' => ['label' => 'Tech marquee', 'fields' => [
        'marquee' => ['Technologies (comma-separated)', 'list', 'React, Laravel, Flutter, AWS, Microsoft Azure, Python, Node.js, MySQL, Docker, Kubernetes, Microsoft 365, Cisco'],
    ]],

    'services' => ['label' => 'Services section', 'fields' => [
        'services_kicker'   => ['Kicker', 'text', 'What We Do'],
        'services_head_1'   => ['Heading', 'text', 'Our'],
        'services_head_2'   => ['Heading (highlighted)', 'text', 'Services'],
        'services_lead'     => ['Lead', 'textarea', 'Five core capabilities and one simple promise: anything IT, we\'ve got you covered.'],
        'services_it_head'  => ['IT services heading', 'text', 'IT Services'],

        'services_cta_title' => ['CTA card title', 'text', 'Have a project in mind?'],
        'services_cta_text'  => ['CTA card text', 'textarea', "Tell us what you need and we'll map out the right solution. No jargon, no obligation."],
        'services_cta_btn'   => ['CTA card button', 'text', 'Get a Free Consultation'],
    ]],

    'travel' => ['label' => 'Travelling services', 'fields' => [
        'services_travel_head' => ['Section heading', 'text', 'Travelling Services'],
    ]],

    'about' => ['label' => 'About section', 'fields' => [
        'about_kicker' => ['Kicker', 'text', 'Who We Are'],
        'about_head_1' => ['Heading', 'text', 'Your Complete'],
        'about_head_2' => ['Heading (highlighted)', 'text', 'Technology Partner'],
        'about_p1'     => ['Paragraph 1', 'textarea', "Pasora Solution Ltd is a full-service IT company built on a simple idea: businesses shouldn't need five different vendors to stay modern, secure and supported. We bring software development, cloud, security, consulting and support under one roof."],
        'about_p2'     => ['Paragraph 2', 'textarea', "Whether you're a startup shipping your first product or an established organisation modernising legacy systems, our team delivers technology that is reliable, secure and built to grow with you."],
        'about_mission'=> ['Mission', 'textarea', 'Make enterprise-grade IT accessible to every business.'],
        'about_vision' => ['Vision', 'textarea', 'To be the most trusted one-stop IT partner in the region.'],
    ]],

    'stats' => ['label' => 'Stats', 'fields' => [
        'stat1_num' => ['Stat 1 number', 'text', '120'],
        'stat1_suf' => ['Stat 1 suffix', 'text', '+'],
        'stat1_lbl' => ['Stat 1 label', 'text', 'Projects Delivered'],
        'stat2_num' => ['Stat 2 number', 'text', '80'],
        'stat2_suf' => ['Stat 2 suffix', 'text', '+'],
        'stat2_lbl' => ['Stat 2 label', 'text', 'Happy Clients'],
        'stat3_num' => ['Stat 3 number', 'text', '10'],
        'stat3_suf' => ['Stat 3 suffix', 'text', '+'],
        'stat3_lbl' => ['Stat 3 label', 'text', 'Years Combined Expertise'],
        'stat4_num' => ['Stat 4 number', 'text', '24'],
        'stat4_suf' => ['Stat 4 suffix', 'text', '/7'],
        'stat4_lbl' => ['Stat 4 label', 'text', 'Support Availability'],
    ]],

    'why' => ['label' => 'Why Us section', 'fields' => [
        'why_kicker' => ['Kicker', 'text', 'Why Pasora'],
        'why_head_1' => ['Heading', 'text', 'Why Businesses'],
        'why_head_2' => ['Heading (highlighted)', 'text', 'Choose Us'],
        'why1_title' => ['Item 1 title', 'text', 'One Partner, Every Need'],
        'why1_desc'  => ['Item 1 text', 'textarea', "Development, cloud, security and support all under one roof, so you never have to juggle vendors or chase down who's responsible."],
        'why2_title' => ['Item 2 title', 'text', 'Security First'],
        'why2_desc'  => ['Item 2 text', 'textarea', 'Every solution we deliver is built with security baked in from day one, not bolted on later.'],
        'why3_title' => ['Item 3 title', 'text', 'Plain-English Advice'],
        'why3_desc'  => ['Item 3 text', 'textarea', "We translate technology into business outcomes, so you always know what you're getting and why."],
        'why4_title' => ['Item 4 title', 'text', 'Support That Shows Up'],
        'why4_desc'  => ['Item 4 text', 'textarea', 'Real people, fast response times and proactive maintenance that fixes issues before you feel them.'],
    ]],

    'cta' => ['label' => 'CTA band', 'fields' => [
        'cta_heading' => ['Heading', 'text', 'Ready to put your IT in safe hands?'],
        'cta_text'    => ['Text', 'textarea', "Anything IT, we've got you covered. Let's talk about your project today."],
        'cta_btn'     => ['Button', 'text', 'Start a Conversation'],
    ]],

    'contact' => ['label' => 'Contact', 'fields' => [
        'contact_kicker'  => ['Kicker', 'text', 'Contact Us'],
        'contact_head_1'  => ['Heading', 'text', "Let's Build Something"],
        'contact_head_2'  => ['Heading (highlighted)', 'text', 'Great Together'],
        'contact_lead'    => ['Lead', 'textarea', "Reach out for a free consultation and we'll get back to you within one business day."],
        'phone_display'   => ['Phone (shown)', 'text', '+233 00 000 0000'],
        'phone_href'      => ['Phone (dial link, digits/+)', 'text', '+233000000000'],
        'email'           => ['Email', 'text', 'info@pasorasolutionltd.com'],
        'office'          => ['Office location', 'text', 'Accra, Ghana'],
        'hours'           => ['Hours line', 'text', 'Monday to Friday, 8:00am to 6:00pm'],
        'hours_emergency' => ['Emergency hours line', 'text', 'Emergency support 24/7'],
    ]],

    'social' => ['label' => 'Social media links', 'fields' => [
        'social_facebook'  => ['Facebook URL', 'text', ''],
        'social_instagram' => ['Instagram URL', 'text', ''],
        'social_x'         => ['X (Twitter) URL', 'text', ''],
        'social_tiktok'    => ['TikTok URL', 'text', ''],
        'social_youtube'   => ['YouTube URL', 'text', ''],
        'social_whatsapp'  => ['WhatsApp link (e.g. https://wa.me/23324...)', 'text', ''],
    ]],

    'footer' => ['label' => 'Footer', 'fields' => [
        'footer_tagline' => ['Footer tagline', 'text', 'Anything IT, we\'ve got you covered.'],
    ]],

];
