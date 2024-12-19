<a href="javascript:void(0)" class="notification-toggler p-0"><i class="las la-bell"></i> <span
        class="bell-ball"></span> </a>
<div class="dropdown-wrapper custom--scrollbar"  v-if="items.length > 0">
    <div class="dropdown-wrapper-header d-flex justify-content-between">
        <span v-cloak>@{{items.length}} @lang('unread messages')</span>
        <span class="cursor-pointer" v-if="items.length > 0" @click.prevent="readAll">@lang("Mark all ase read")</span>
    </div>
    <div class="dropdown-body">
        <ul class="notifications">
            <li class="notification-item cursor-pointer"  v-for="(item, index) in items" @click.prevent="readAt(item.id, item.description.link)"  >
                <div class="note-thumb">
                    <i :class="item.description.icon" ></i>
                </div>
                <div class="note-content">
                    <p v-cloak v-html="item.description.text"></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="time"  v-cloak>@{{ item.formatted_date }}</span>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</div>
<div class="dropdown-wrapper custom--scrollbar"  v-else>
    <div class="dropdown-wrapper-header d-flex justify-content-between">
        <span>@lang('No notifications')</span>
    </div>
</div>
