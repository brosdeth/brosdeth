//
import NotFoundComponent from './NotFoundComponent.vue';
import noPermission from './permission/NoPermission.vue';

// Item
import Categories from './items/Categories.vue';
import Item from './items/Item.vue';
import NewItem from './items/NewItem.vue';
import Attribute from './items/Attribute.vue';
import ItemShow from './items/ItemShow.vue';


import Myprofile from './admin/Myprofile.vue';
import User from './admin/User.vue';
import UserRole from './admin/UserRole.vue';
import RoleCreate from './admin/RoleCreate.vue';
import Profile from './admin/Profile.vue';


export const routes = [
  { path: '/my-profile', name: 'Myprofile', component: Myprofile, meta: { title: 'Myprofile' } },
  { path: '/UserRole', name: 'UserRole', component: UserRole, meta: { title: 'User Role' } },
  { path: '/role-create/:id?', name: 'RoleCreate', component: RoleCreate, meta: { title: 'User Role' } },
  { path: '/Profile', name: 'Profile', component: Profile, meta: { title: 'Profile' } },
  { path: '/user', name: 'User', component: User, meta: { title: 'User' } },
  { path: '/categories', name: 'Categories', component: Categories, meta: { title: 'Categories' } },
  { path: '/items', name: 'Item', component: Item, meta: { title: 'Item' } },
  { path: '/attributes', name: 'Attribute', component: Attribute, meta: { title: 'Attribute' } },

  { path: '/new-items/:id?', name: 'NewItem', component: NewItem, meta: { title: 'New Item' } },

  { path: '/item-show/:id', name: 'ItemShow', component: ItemShow, meta: { title: 'Item Show' } },
  //Report

  { path: '*', component: NotFoundComponent, meta: { title: 'Not Found' } },
  { path: '/no-permission', name: 'noPermission', component: noPermission, meta: { title: 'No Permission' } },

]
