<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="relative flex size-full min-h-screen flex-col bg-[#181411] dark group/design-root overflow-x-hidden" style='font-family: Lexend, "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#392f28] px-10 py-3">
          <div class="flex items-center gap-4 text-white">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M39.5563 34.1455V13.8546C39.5563 15.708 36.8773 17.3437 32.7927 18.3189C30.2914 18.916 27.263 19.2655 24 19.2655C20.737 19.2655 17.7086 18.916 15.2073 18.3189C11.1227 17.3437 8.44365 15.708 8.44365 13.8546V34.1455C8.44365 35.9988 11.1227 37.6346 15.2073 38.6098C17.7086 39.2069 20.737 39.5564 24 39.5564C27.263 39.5564 30.2914 39.2069 32.7927 38.6098C36.8773 37.6346 39.5563 35.9988 39.5563 34.1455Z"
                  fill="currentColor"
                ></path>
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M10.4485 13.8519C10.4749 13.9271 10.6203 14.246 11.379 14.7361C12.298 15.3298 13.7492 15.9145 15.6717 16.3735C18.0007 16.9296 20.8712 17.2655 24 17.2655C27.1288 17.2655 29.9993 16.9296 32.3283 16.3735C34.2508 15.9145 35.702 15.3298 36.621 14.7361C37.3796 14.246 37.5251 13.9271 37.5515 13.8519C37.5287 13.7876 37.4333 13.5973 37.0635 13.2931C36.5266 12.8516 35.6288 12.3647 34.343 11.9175C31.79 11.0295 28.1333 10.4437 24 10.4437C19.8667 10.4437 16.2099 11.0295 13.657 11.9175C12.3712 12.3647 11.4734 12.8516 10.9365 13.2931C10.5667 13.5973 10.4713 13.7876 10.4485 13.8519ZM37.5563 18.7877C36.3176 19.3925 34.8502 19.8839 33.2571 20.2642C30.5836 20.9025 27.3973 21.2655 24 21.2655C20.6027 21.2655 17.4164 20.9025 14.7429 20.2642C13.1498 19.8839 11.6824 19.3925 10.4436 18.7877V34.1275C10.4515 34.1545 10.5427 34.4867 11.379 35.027C12.298 35.6207 13.7492 36.2054 15.6717 36.6644C18.0007 37.2205 20.8712 37.5564 24 37.5564C27.1288 37.5564 29.9993 37.2205 32.3283 36.6644C34.2508 36.2054 35.702 35.6207 36.621 35.027C37.4573 34.4867 37.5485 34.1546 37.5563 34.1275V18.7877ZM41.5563 13.8546V34.1455C41.5563 36.1078 40.158 37.5042 38.7915 38.3869C37.3498 39.3182 35.4192 40.0389 33.2571 40.5551C30.5836 41.1934 27.3973 41.5564 24 41.5564C20.6027 41.5564 17.4164 41.1934 14.7429 40.5551C12.5808 40.0389 10.6502 39.3182 9.20848 38.3869C7.84205 37.5042 6.44365 36.1078 6.44365 34.1455L6.44365 13.8546C6.44365 12.2684 7.37223 11.0454 8.39581 10.2036C9.43325 9.3505 10.8137 8.67141 12.343 8.13948C15.4203 7.06909 19.5418 6.44366 24 6.44366C28.4582 6.44366 32.5797 7.06909 35.657 8.13948C37.1863 8.67141 38.5667 9.3505 39.6042 10.2036C40.6278 11.0454 41.5563 12.2684 41.5563 13.8546Z"
                  fill="currentColor"
                ></path>
              </svg>
            </div>
            <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">Gravity</h2>
          </div>
          <div class="flex flex-1 justify-end gap-8">
            <div class="flex items-center gap-9">
              <a class="text-white text-sm font-medium leading-normal" href="#">Classes</a>
              <a class="text-white text-sm font-medium leading-normal" href="#">Trainers</a>
              <a class="text-white text-sm font-medium leading-normal" href="#">Locations</a>
              <a class="text-white text-sm font-medium leading-normal" href="#">Blog</a>
            </div>
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#ec6d13] text-white text-sm font-bold leading-normal tracking-[0.015em]"
            >
              <span class="truncate">Join Now</span>
            </button>
          </div>
        </header>
        <div class="px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
            <div class="@container">
              <div class="@[480px]:p-4">
                <div
                  class="flex min-h-[480px] flex-col gap-6 bg-cover bg-center bg-no-repeat @[480px]:gap-8 @[480px]:rounded-xl items-start justify-end px-4 pb-10 @[480px]:px-10"
                  style='background-image: linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.4) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuCNYmoESzgtDoT004tL2A9uwYgjge76FOWYmso1ihJq0t4ayX2JdHIGTX1mwVCwweeZRxMYvQQXl_A4vSXRUbqL4-OeXLw183Bl8G5lOiOGRS-6mL6YOHYm9MK4rKPvn18otZk23KGzGbKblpmrUkVsFvlvh82Fvbareenr8WSL6ukE5b5AyR2MdKZtkWTlaL0Jjq_g4TfQWiwMk7pXoRG1p49ZDL2v65qtHgRIjc_rOSPDHTZ5hUqJkhoEY4hWtW1y0bMGnhZoFcY");'
                >
                  <div class="flex flex-col gap-2 text-left">
                    <h1
                      class="text-white text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-5xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]"
                    >
                      Elevate Your Fitness Journey
                    </h1>
                    <h2 class="text-white text-sm font-normal leading-normal @[480px]:text-base @[480px]:font-normal @[480px]:leading-normal">
                      Experience the ultimate fitness destination with state-of-the-art equipment, expert trainers, and a vibrant community.
                    </h2>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-[#ec6d13] text-white text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em]"
                  >
                    <span class="truncate">Join Now</span>
                  </button>
                </div>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Promotions</h2>
            <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden">
              <div class="flex items-stretch p-4 gap-3">
                <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl flex flex-col"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCbUrID5wwe45HEQIclc1EYCtikhcx5ZxOc5FNwNIrCQldW76ZdQyLzug0yr7MlvONWE1MJ1bLzBHa68rg_r9eJfUFBGWARvqqT2n53dAWoJyXUfGoa-no0A85nPD_yRV9zYo0IObWAbqrzu0osjqJvW1-siqFN19mqQre2w0kQuDDfpQ4qKyixeiomgT4_YqYsluLy1_ytNwpxQen3fwnX2tZCS5UCDFedP0JyVOkpywWsbQDxfSMVU3xcpJiO7CJEW0VhQEJWW-8");'
                  ></div>
                  <div>
                    <p class="text-white text-base font-medium leading-normal">Group Fitness Classes</p>
                    <p class="text-[#b9a89d] text-sm font-normal leading-normal">Join our high-energy group fitness classes led by certified instructors.</p>
                  </div>
                </div>
                <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl flex flex-col"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBSRupgtdPzUmkceNGeMxB-aGTH0qZQPN1tvT7AtZjyG55C4YGUP-9tycPQ8bIXUHeAH-GIF7JOIl3bbuj2w2gC9IxQgYhRYsyZLDcmbo0qxpeAQ2oU4-wR2qy7As6uazKhMPMvS7VLRL0iSpN0R0sMajw72E6LpOS6EBn2OMmH9meafaGSBNgb42B85de0VZyOHzRjVMtiDWiEH48gjc3REO7GwT3oBW5z4CEfCGuuaOseqbBUq22idPRH7Sn5nL_979DT4qu2ijo");'
                  ></div>
                  <div>
                    <p class="text-white text-base font-medium leading-normal">Personal Training</p>
                    <p class="text-[#b9a89d] text-sm font-normal leading-normal">Achieve your fitness goals with personalized training programs.</p>
                  </div>
                </div>
                <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl flex flex-col"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBAU1FQzKaB1T8JA336_bmKzaBaWMTTQ5Til0-79s21N_IneLej8XkvK2N-8VZBtv9UJh2urTpaRob9E8q08T16k3haT5PCCjpZ-KsSnur7i5J-Zyp-jUn5cjxLFAUvVJlgYp8EqFYMlwRGpBzN4TOcWjDE_wI8ftU9ATrokf7PjJyAG_OS7I8tFCtFAMv05t7S_MB-K66TalgB_sFaCUvwb-AV_4vmxuABgPWPpK4p0Qs0SQMuojaK_n90VZaBKMhtVh80QIJUzBY");'
                  ></div>
                  <div>
                    <p class="text-white text-base font-medium leading-normal">State-of-the-Art Equipment</p>
                    <p class="text-[#b9a89d] text-sm font-normal leading-normal">Work out with the latest and most advanced gym equipment.</p>
                  </div>
                </div>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Upcoming Events</h2>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(158px,1fr))] gap-3 p-4">
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBiwhNL81bZ-nsT-dih2-4lam5UVnryWIjnDwbHdmD_5UZzhDGvfCMqVx0DVHiWu76DrxX5jYzg-6GkVEzyiu-2WbBbi3hBCbCKRraVx83ZKhxv_bh5jSugwml0hEa_nbU3uSNaa4YtkU2c3kwMuZpi6cS-UO6eo0xVM5n1q22Hv3j42TSLOUWIe_d5V446tGK6jVc0Tfi93yyiFRjKmzW1i1jK2OFt1RXkM8ZHFxWdEUlmEuAmBVw501KPUf0RWsC1dYHIIR2sAog");'
                ></div>
                <div>
                  <p class="text-white text-base font-medium leading-normal">Yoga Workshop</p>
                  <p class="text-[#b9a89d] text-sm font-normal leading-normal">Join our yoga workshop to improve flexibility and mindfulness.</p>
                </div>
              </div>
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBdn7oR7lqKKO0vfnYFLtxelATY2qziZ3HzDxeSzSArwcj9muhfKqy4tq2kfEhz8HWit-6laEzd5nrp3aKqESH86mvVkRjBV9rRxtX9YmimgwEUL2EFHx75KRTcGE9awOw-lMhKOg6Uz2KbmosRbSecC3tHEViG2J6Gq1guIbteCqhE4wWnvrf_8nzmcPJgbU9bsj9nyKEIwNfQB1cEjyO_Ws1-G7BQUcjhOM0FZmBqz6zfhK2wJWNtc_QoA3GqQaO-ZfA5LMx6DuQ");'
                ></div>
                <div>
                  <p class="text-white text-base font-medium leading-normal">Strength Training Seminar</p>
                  <p class="text-[#b9a89d] text-sm font-normal leading-normal">Learn advanced strength training techniques from our expert trainers.</p>
                </div>
              </div>
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBx7lB1oHr4RSTTJZXOPYyZ0t2oIYMJOEGe1q4iww_gxpEiOxa36OFVkFsCiJea7KypW61e1vDGe3rBibqy4GHF0tiEXrc4tJzoQybDPHvDeeR5Q9jGlt3NUjXpDdCVY9qpStYZw5lvVrBMMYQTAwApECBCIlZakg77TVCgXJwpdaPheLxYZaUSZc-W69Eg9IpMp2oSgIHxDJ3vR7mYJ-gK1rhUjJDlk9HCJ90V360XHZUHmqLbHMbVlvq9cUPidwimzvwx-iW_7Ec");'
                ></div>
                <div>
                  <p class="text-white text-base font-medium leading-normal">Nutrition Workshop</p>
                  <p class="text-[#b9a89d] text-sm font-normal leading-normal">Discover the secrets to a healthy diet and optimal nutrition.</p>
                </div>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Our Locations</h2>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(158px,1fr))] gap-3 p-4">
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCMSwTNJ-UXTw9eNtK2OkVNvsuRLm2956ktyzIRY_bgebyVJ5f2HRPABopFTEJyUrWYwpMFEi-n1cWdANeOAskQPcM_x-61U10-NsxBtAcofeKV6yMiTyFrFcPh9KIXzV6_NuzOnvfYLpgRRHykYrIHHLkJ7nVs6-DQrGKF7AuD4GJbYMmgjxEHA296cNu_pLA9k5w26v8CMklLktXst0Om3DiTcxLfGEHigQdKQ2dChpK6Z1r_oB1Dk9MGogbS7Q9Nqxjl1QDojcc");'
                ></div>
                <div>
                  <p class="text-white text-base font-medium leading-normal">Downtown</p>
                  <p class="text-[#b9a89d] text-sm font-normal leading-normal">Find us in the heart of the city.</p>
                </div>
              </div>
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA5LHgYg3caHiEjZqizURm-J4FFeHBM5itM_42nVUm6uPviUE4JiFZAAkNIzYkkliBLA1l37FMMpRVL0f8dLkj3atftU8_Zs_qvKiEIHnDQiLN-TIAX-kXccGnyiX8BUikZSF6XxP5_suaTZGh87hMhIyids5KnyZxQk3gxgva-nu3HqnCWT2FlKVNvSvqOs2um6A-mh_5cpdLKCrHpSYCYFc7c92mbRjRMRlfGGpyK1-QMhJfAUfBwmaYkwnA0tda96HJ2l-xK_C0");'
                ></div>
                <div>
                  <p class="text-white text-base font-medium leading-normal">Uptown</p>
                  <p class="text-[#b9a89d] text-sm font-normal leading-normal">Located in the vibrant uptown district.</p>
                </div>
              </div>
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAPwXYuQ4iWz-G8LLEudgtASZGdLq_4apgvZ73TQI9B0XFK_cfO359zOEjadtyDrJR63tOSu9KDbSSvh8iFCIBS-nvTJo-8yRXeLc02ZJ5x57MXPPimZxA3AzquvE3vx_rTTYXeFfuxCaiezQ_9Ycpv-aTq91UnJ5UYzCh49hL_qG2G_DQ_WXGlClqjdbdFoza4BGdOhlWFplcM-qRAAvZPjW0CCn-HTpvCM9gSjcfuMaBRnkTETdLcB2pll-bylYFdCEvr2d-RKa4");'
                ></div>
                <div>
                  <p class="text-white text-base font-medium leading-normal">Midtown</p>
                  <p class="text-[#b9a89d] text-sm font-normal leading-normal">Conveniently situated in midtown.</p>
                </div>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Customer Reviews</h2>
            <div class="flex flex-col gap-8 overflow-x-hidden bg-[#181411] p-4">
              <div class="flex flex-col gap-3 bg-[#181411]">
                <div class="flex items-center gap-3">
                  <div
                    class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDCsE1Qr4Y3wtiB4fToY2-kSKyuQm1cvI79gLFZ_qRU4QzNkxp1_aoz7y1-xlpbm7AJUZEc3Bm87KH4xG7rvEeFYHrI2oHtMcgiU2Uw_U_2TDsItVUT5AJ58fN9O-jfjSz_NtdqVDpgFSx8I_66Misj8DRqJrd2MDSRW_wyPu2XsI0YqvrSb2v4fFEmiNi56GRlmZjl0-kEzLaAGsa-dCzYsw2y3aijYmZ-lGluggHPLtc5thUDkUUW3NTrZrtTB6W7tXeOjJM8MCE");'
                  ></div>
                  <div class="flex-1">
                    <p class="text-white text-base font-medium leading-normal">Sophia Carter</p>
                    <p class="text-[#b9a89d] text-sm font-normal leading-normal">2 months ago</p>
                  </div>
                </div>
                <div class="flex gap-0.5">
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                </div>
                <p class="text-white text-base font-normal leading-normal">
                  Gravity has transformed my fitness journey. The trainers are incredibly supportive, and the community is fantastic.
                </p>
              </div>
              <div class="flex flex-col gap-3 bg-[#181411]">
                <div class="flex items-center gap-3">
                  <div
                    class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCLjUXY1RHtHoxVtQM1WJLbsmNqExsEuvimcBFilRK3N90h7FlrsJNJwlHV8SvOUXHT7m1HR0ydO0rwp9dgcAQHs0k8VN0I4j9Krm4Ar1Om6Q9o-Rt27blmbHuTU3vxbR-St44uygpvhB79PcfLsxndBkJ0HBirjNy9ysyDbmNbWGxMYvTIikwkzpSZPoGBsjsezyfSi93rbPubfgBVEwYYCBWEIzdZIlnmTVNg9ZCdH6GBdQ0EgolO6pHYLgCEk6BYKXpCDEgG6Ng");'
                  ></div>
                  <div class="flex-1">
                    <p class="text-white text-base font-medium leading-normal">Ethan Bennett</p>
                    <p class="text-[#b9a89d] text-sm font-normal leading-normal">3 months ago</p>
                  </div>
                </div>
                <div class="flex gap-0.5">
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-[#776355]" data-icon="Star" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M239.2,97.29a16,16,0,0,0-13.81-11L166,81.17,142.72,25.81h0a15.95,15.95,0,0,0-29.44,0L90.07,81.17,30.61,86.32a16,16,0,0,0-9.11,28.06L66.61,153.8,53.09,212.34a16,16,0,0,0,23.84,17.34l51-31,51.11,31a16,16,0,0,0,23.84-17.34l-13.51-58.6,45.1-39.36A16,16,0,0,0,239.2,97.29Zm-15.22,5-45.1,39.36a16,16,0,0,0-5.08,15.71L187.35,216v0l-51.07-31a15.9,15.9,0,0,0-16.54,0l-51,31h0L82.2,157.4a16,16,0,0,0-5.08-15.71L32,102.35a.37.37,0,0,1,0-.09l59.44-5.14a16,16,0,0,0,13.35-9.75L128,32.08l23.2,55.29a16,16,0,0,0,13.35,9.75L224,102.26S224,102.32,224,102.33Z"
                      ></path>
                    </svg>
                  </div>
                </div>
                <p class="text-white text-base font-normal leading-normal">The equipment is top-notch, and the classes are challenging. I've seen great results since joining.</p>
              </div>
              <div class="flex flex-col gap-3 bg-[#181411]">
                <div class="flex items-center gap-3">
                  <div
                    class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDPV-ueOmaLs7INHIqjhkrkgmoosG79ORrw4O22mNHUQAB9sZI-Rk5ZUYY_wsVfw-OLMqFR1gULb19l5fBLOaLtDQqEz_VCCoorYNxyiVyVhMVgCBFUGHn8oM9MMSUd_uhLBdh0ptryWCpPuIdzhUYgNkLVZdLfIJLfAoYAlBTBxo_2YyCx7o2w8CpfQhctnTFrNM3ldu2DXw2iSgFmNZikMp05ddCaIYk_TCD_8eSycanzagIHgPwt7QSTJsB5G6VIAxO4ts1xfU8");'
                  ></div>
                  <div class="flex-1">
                    <p class="text-white text-base font-medium leading-normal">Olivia Hayes</p>
                    <p class="text-[#b9a89d] text-sm font-normal leading-normal">4 months ago</p>
                  </div>
                </div>
                <div class="flex gap-0.5">
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                  <div class="text-white" data-icon="Star" data-size="20px" data-weight="fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z"
                      ></path>
                    </svg>
                  </div>
                </div>
                <p class="text-white text-base font-normal leading-normal">
                  I love the positive atmosphere at Gravity. The staff is friendly, and the facilities are always clean.
                </p>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Membership Options</h2>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(228px,1fr))] gap-2.5 px-4 py-3 @3xl:grid-cols-4">
              <div class="flex flex-1 flex-col gap-4 rounded-xl border border-solid border-[#54453b] bg-[#27201c] p-6">
                <div class="flex flex-col gap-1">
                  <h1 class="text-white text-base font-bold leading-tight">Basic</h1>
                  <p class="flex items-baseline gap-1 text-white">
                    <span class="text-white text-4xl font-black leading-tight tracking-[-0.033em]">$29</span>
                    <span class="text-white text-base font-bold leading-tight">/month</span>
                  </p>
                </div>
                <button
                  class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#392f28] text-white text-sm font-bold leading-normal tracking-[0.015em]"
                >
                  <span class="truncate">Join Now</span>
                </button>
                <div class="flex flex-col gap-2">
                  <div class="text-[13px] font-normal leading-normal flex gap-3 text-white">
                    <div class="text-white" data-icon="Check" data-size="20px" data-weight="regular">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path>
                      </svg>
                    </div>
                    Access to gym facilities
                  </div>
                  <div class="text-[13px] font-normal leading-normal flex gap-3 text-white">
                    <div class="text-white" data-icon="Check" data-size="20px" data-weight="regular">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path>
                      </svg>
                    </div>
                    Group fitness classes
                  </div>
                </div>
              </div>
              <div class="flex flex-1 flex-col gap-4 rounded-xl border border-solid border-[#54453b] bg-[#27201c] p-6">
                <div class="flex flex-col gap-1">
                  <h1 class="text-white text-base font-bold leading-tight">Premium</h1>
                  <p class="flex items-baseline gap-1 text-white">
                    <span class="text-white text-4xl font-black leading-tight tracking-[-0.033em]">$49</span>
                    <span class="text-white text-base font-bold leading-tight">/month</span>
                  </p>
                </div>
                <button
                  class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#392f28] text-white text-sm font-bold leading-normal tracking-[0.015em]"
                >
                  <span class="truncate">Join Now</span>
                </button>
                <div class="flex flex-col gap-2">
                  <div class="text-[13px] font-normal leading-normal flex gap-3 text-white">
                    <div class="text-white" data-icon="Check" data-size="20px" data-weight="regular">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path>
                      </svg>
                    </div>
                    All Basic features
                  </div>
                  <div class="text-[13px] font-normal leading-normal flex gap-3 text-white">
                    <div class="text-white" data-icon="Check" data-size="20px" data-weight="regular">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path>
                      </svg>
                    </div>
                    Personal training sessions
                  </div>
                  <div class="text-[13px] font-normal leading-normal flex gap-3 text-white">
                    <div class="text-white" data-icon="Check" data-size="20px" data-weight="regular">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path>
                      </svg>
                    </div>
                    Exclusive classes
                  </div>
                </div>
              </div>
              <div class="flex flex-1 flex-col gap-4 rounded-xl border border-solid border-[#54453b] bg-[#27201c] p-6">
                <div class="flex flex-col gap-1">
                  <h1 class="text-white text-base font-bold leading-tight">Elite</h1>
                  <p class="flex items-baseline gap-1 text-white">
                    <span class="text-white text-4xl font-black leading-tight tracking-[-0.033em]">$79</span>
                    <span class="text-white text-base font-bold leading-tight">/month</span>
                  </p>
                </div>
                <button
                  class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#392f28] text-white text-sm font-bold leading-normal tracking-[0.015em]"
                >
                  <span class="truncate">Join Now</span>
                </button>
                <div class="flex flex-col gap-2">
                  <div class="text-[13px] font-normal leading-normal flex gap-3 text-white">
                    <div class="text-white" data-icon="Check" data-size="20px" data-weight="regular">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path>
                      </svg>
                    </div>
                    All Premium features
                  </div>
                  <div class="text-[13px] font-normal leading-normal flex gap-3 text-white">
                    <div class="text-white" data-icon="Check" data-size="20px" data-weight="regular">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path>
                      </svg>
                    </div>
                    Unlimited personal training
                  </div>
                  <div class="text-[13px] font-normal leading-normal flex gap-3 text-white">
                    <div class="text-white" data-icon="Check" data-size="20px" data-weight="regular">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path>
                      </svg>
                    </div>
                    VIP access
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <footer class="flex justify-center">
          <div class="flex max-w-[960px] flex-1 flex-col">
            <footer class="flex flex-col gap-6 px-5 py-10 text-center @container">
              <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
                <a class="text-[#b9a89d] text-base font-normal leading-normal min-w-40" href="#">Contact Us</a>
                <a class="text-[#b9a89d] text-base font-normal leading-normal min-w-40" href="#">Privacy Policy</a>
                <a class="text-[#b9a89d] text-base font-normal leading-normal min-w-40" href="#">Terms of Service</a>
              </div>
              <p class="text-[#b9a89d] text-base font-normal leading-normal">@2024 Gravity Fitness. All rights reserved.</p>
            </footer>
          </div>
        </footer>
      </div>
    </div>
</template>
