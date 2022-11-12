<template>
    <el-config-provider :locale="ru">
        <el-row :gutter="20">
            <el-col :md="8">
                <el-form label-position="top" style="padding: 0 1rem">
                    <el-form-item label="Search by name">
                        <el-input v-model="filters.query" />
                    </el-form-item>

                    <el-form-item label="Filter by price range">
                        <el-slider v-model="filters.price" range :min="baseFilters.price.min" :max="baseFilters.price.max" />
                    </el-form-item>

                    <el-form-item label="Filter by bedrooms">
                        <el-radio-group v-model="filters.bedrooms">
                            <el-radio :key="0" :label="0">Any</el-radio>
                            <el-radio v-for="i in baseFilters.bedrooms" :key="i" :label="i">{{ i }}</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="Filter by bathrooms">
                        <el-radio-group v-model="filters.bathrooms">
                            <el-radio :key="0" :label="0">Any</el-radio>
                            <el-radio v-for="i in baseFilters.bathrooms" :key="i" :label="i">{{ i }}</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="Filter by storeys">
                        <el-radio-group v-model="filters.storeys">
                            <el-radio :key="0" :label="0">Any</el-radio>
                            <el-radio v-for="i in baseFilters.storeys" :key="i" :label="i">{{ i }}</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="Filter by garages">
                        <el-radio-group v-model="filters.garages">
                            <el-radio :key="0" :label="0">Any</el-radio>
                            <el-radio v-for="i in baseFilters.garages" :key="i" :label="i">{{ i }}</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-button :disabled="!Object.keys(getFilters()).length" @click="resetFilters">Reset Filters</el-button>
                </el-form>
            </el-col>
            <el-col :md="16">
                <el-table v-loading="loading" :data="result.data" border :default-sort="{ prop: sort, order: dir }" @sort-change="onSort">
                    <el-table-column prop="title" label="Title" sortable="custom" />
                    <el-table-column prop="price" label="Price" sortable="custom" />
                    <el-table-column prop="bedrooms" label="Bedrooms" sortable="custom" />
                    <el-table-column prop="bathrooms" label="Bathrooms" sortable="custom" />
                    <el-table-column prop="storeys" label="Storeys" sortable="custom" />
                    <el-table-column prop="garages" label="Garages" sortable="custom" />
                </el-table>

                <el-pagination v-model:current-page="page" layout="prev, pager, next, ->, total" :page-size="limit" :total="result.total" />
            </el-col>
        </el-row>
    </el-config-provider>
</template>

<script setup lang="ts">
import {ElConfigProvider, ElRow, ElCol, ElTable, ElTableColumn, ElForm, ElFormItem, ElInput, ElSlider, ElRadioGroup, ElPagination, ElRadio, ElButton, vLoading} from 'element-plus/dist/index.full.js'
import ru from 'element-plus/dist/locale/ru.js'
import { Ref } from 'vue'
import {watch} from "#imports";

const baseURL = useNuxtApp().$config.API_URL
const rows = ref([])
const page = ref(1)
const sort = ref('title')
const dir = ref('ascending')
const loading = ref(false)
const limit = ref(10)
const filters: {[key: string]: any} = ref({query: ''})
const query = ref(getQueryParams())

const {data: baseFilters}: {data: Ref} = await useFetch('house-filters', { baseURL })
Object.keys(baseFilters.value).forEach((key) => {
    filters.value[key] = key === 'price' ? Object.values(baseFilters.value[key]) : 0
})

const { data: result, pending, refresh } = await useFetch('houses', { query, baseURL})

function getQueryParams() {
    const query: {[key: string]: string} = {}
    if (page.value > 1) {
        query.page = String(page.value)
    }
    query.sort = String(sort.value)
    query.dir = String(dir.value)
    query.limit = String(limit.value)

    return {...query, ...getFilters()}
}

function getFilters() {
    const query: {[key: string]: string} = {}
    Object.keys(filters.value).forEach((key: string) => {
        let value = filters.value[key]
        if (key === 'price') {
            value = value[0] > baseFilters.value.price.min || value[1] < baseFilters.value.price.max
                ? value.join(',')
                : null
        }
        if (value) {
            query[key] = String(value)
        }
    })

    return query
}

function resetFilters() {
    filters.value.query = ''
    Object.keys(baseFilters.value).forEach((key) => {
        filters.value[key] = key === 'price' ? Object.values(baseFilters.value[key]) : 0
    })
}

function updateResults() {
    if (loading.value) {
        return
    }
    loading.value = true
    // Throttle requests
    setTimeout(() => {
        query.value = getQueryParams()
    }, 250)
}

function onSort({prop, order}: {prop: string, order: string}) {
    sort.value = prop
    dir.value = order
}

watch(filters, () => {
    page.value = 1
    updateResults()
}, {deep: true})

watch([sort, dir, page, limit], updateResults)

watch(pending, (newValue) => {
    if (!newValue) {
        loading.value = false
    }
})
</script>
